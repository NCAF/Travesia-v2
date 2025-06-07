<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Order;
use App\Traits\MidtransConfigTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class MidtransCallbackController extends Controller
{
    use MidtransConfigTrait;

    /**
     * Handle Midtrans webhook notification
     */
    public function notification(Request $request)
    {
        try {
            // Configure Midtrans using trait
            $this->configureMidtrans();
            
            // Get the notification data
            $notif = new \Midtrans\Notification();
            
            Log::info('Midtrans Notification Received:', [
                'transaction_id' => $notif->transaction_id,
                'order_id' => $notif->order_id,
                'transaction_status' => $notif->transaction_status,
                'payment_type' => $notif->payment_type ?? null,
                'fraud_status' => $notif->fraud_status ?? null,
                'full_data' => $request->all()
            ]);

            // Verify signature for security
            $this->verifySignature($request, $notif);

            // Extract order ID from Midtrans order ID
            // Format: TXN-{order_id}-{timestamp} or ORDER-{order_id}-{timestamp}
            $originalOrderId = $this->extractOrderId($notif->order_id);
            
            if (!$originalOrderId) {
                Log::error('Cannot extract original order ID from: ' . $notif->order_id);
                return response()->json(['status' => 'error', 'message' => 'Invalid order ID format'], 400);
            }

            // Begin database transaction
            DB::beginTransaction();

            try {
                // Find or create transaction record
                $transaction = Transaction::updateOrCreate(
                    [
                        'transaction_id' => $notif->transaction_id
                    ],
                    [
                        'order_id' => $originalOrderId,
                        'payment_method' => $notif->payment_type ?? 'unknown',
                        'gross_amount' => $notif->gross_amount ?? 0,
                        'status' => $this->mapMidtransStatus($notif->transaction_status),
                        'payment_code' => $this->getPaymentCode($notif),
                        'fraud_status' => $notif->fraud_status ?? null,
                        'transaction_time' => $notif->transaction_time ? \Carbon\Carbon::parse($notif->transaction_time) : now(),
                        'settlement_time' => $notif->settlement_time ? \Carbon\Carbon::parse($notif->settlement_time) : null,
                        'midtrans_response' => $request->all(),
                    ]
                );

                // Update order status based on transaction status
                $this->updateOrderStatus($originalOrderId, $notif->transaction_status, $notif->fraud_status ?? null);

                DB::commit();

                Log::info('Transaction updated successfully:', [
                    'transaction_id' => $transaction->id,
                    'order_id' => $originalOrderId,
                    'status' => $transaction->status
                ]);

                return response()->json(['status' => 'success']);

            } catch (Exception $e) {
                DB::rollback();
                Log::error('Database error in Midtrans callback: ' . $e->getMessage());
                return response()->json(['status' => 'error', 'message' => 'Database error'], 500);
            }

        } catch (\Midtrans\Exception $e) {
            Log::error('Midtrans Exception in callback: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Midtrans error'], 500);
            
        } catch (Exception $e) {
            Log::error('General Exception in Midtrans callback: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Server error'], 500);
        }
    }

    /**
     * Verify Midtrans signature for security
     */
    private function verifySignature(Request $request, $notif)
    {
        $serverKey = config('midtrans.serverKey');
        $orderId = $notif->order_id;
        $statusCode = $notif->status_code;
        $grossAmount = $notif->gross_amount;
        
        $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        
        if ($signatureKey !== $notif->signature_key) {
            Log::error('Invalid Midtrans signature', [
                'expected' => $signatureKey,
                'received' => $notif->signature_key
            ]);
            throw new Exception('Invalid signature');
        }
    }

    /**
     * Extract original order ID from Midtrans order ID
     */
    private function extractOrderId($midtransOrderId)
    {
        // Format: TXN-{order_id}-{timestamp} or ORDER-{order_id}-{timestamp}
        if (preg_match('/^(TXN|ORDER)-(\d+)-\d+$/', $midtransOrderId, $matches)) {
            return (int) $matches[2];
        }
        
        // Alternative format check
        if (preg_match('/^[A-Z]+-(\d+)-\d+$/', $midtransOrderId, $matches)) {
            return (int) $matches[1];
        }
        
        return null;
    }

    /**
     * Map Midtrans transaction status to our status
     */
    private function mapMidtransStatus($midtransStatus)
    {
        $statusMap = [
            'capture' => Transaction::STATUS_CAPTURE,
            'settlement' => Transaction::STATUS_SETTLEMENT,
            'pending' => Transaction::STATUS_PENDING,
            'deny' => Transaction::STATUS_DENY,
            'cancel' => Transaction::STATUS_CANCEL,
            'expire' => Transaction::STATUS_EXPIRE,
            'failure' => Transaction::STATUS_FAILURE,
        ];

        return $statusMap[$midtransStatus] ?? Transaction::STATUS_PENDING;
    }

    /**
     * Get payment code (VA number, QRIS code, etc.) from notification
     */
    private function getPaymentCode($notif)
    {
        // For Bank Transfer (VA)
        if (isset($notif->va_numbers) && is_array($notif->va_numbers) && !empty($notif->va_numbers)) {
            return $notif->va_numbers[0]->va_number ?? null;
        }

        // For QRIS
        if (isset($notif->qr_string)) {
            return $notif->qr_string;
        }

        // For other payment methods
        if (isset($notif->payment_code)) {
            return $notif->payment_code;
        }

        return null;
    }

    /**
     * Update order status based on transaction status
     */
    private function updateOrderStatus($orderId, $transactionStatus, $fraudStatus = null)
    {
        $order = Order::find($orderId);
        
        if (!$order) {
            Log::warning('Order not found for ID: ' . $orderId);
            return;
        }

        $newStatus = $order->status; // Default to current status

        // Update order status based on transaction status
        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                if ($fraudStatus === 'accept' || $fraudStatus === null) {
                    $newStatus = 'paid';
                }
                break;
                
            case 'pending':
                $newStatus = 'pending_payment';
                break;
                
            case 'deny':
            case 'cancel':
            case 'expire':
            case 'failure':
                $newStatus = 'canceled';
                break;
        }

        if ($newStatus !== $order->status) {
            $order->update(['status' => $newStatus]);
            
            Log::info('Order status updated:', [
                'order_id' => $orderId,
                'old_status' => $order->status,
                'new_status' => $newStatus,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus
            ]);
        }
    }

    /**
     * Handle finish redirect from Midtrans (optional)
     */
    public function finish(Request $request)
    {
        $orderId = $request->get('order_id');
        $statusCode = $request->get('status_code');
        $transactionStatus = $request->get('transaction_status');
        
        Log::info('Midtrans Finish Redirect:', [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'transaction_status' => $transactionStatus
        ]);

        // Extract original order ID
        $originalOrderId = $this->extractOrderId($orderId);
        
        if ($originalOrderId) {
            return redirect()->route('user.order-detail', $originalOrderId)
                           ->with('success', 'Payment completed successfully!');
        }

        return redirect()->route('user.order-lists')
                       ->with('info', 'Payment status updated.');
    }

    /**
     * Handle error redirect from Midtrans (optional)
     */
    public function error(Request $request)
    {
        $orderId = $request->get('order_id');
        $statusCode = $request->get('status_code');
        $transactionStatus = $request->get('transaction_status');
        
        Log::info('Midtrans Error Redirect:', [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'transaction_status' => $transactionStatus
        ]);

        return redirect()->route('user.order-lists')
                       ->with('error', 'Payment failed. Please try again.');
    }

    /**
     * Handle unfinish redirect from Midtrans (optional)
     */
    public function unfinish(Request $request)
    {
        $orderId = $request->get('order_id');
        
        Log::info('Midtrans Unfinish Redirect:', [
            'order_id' => $orderId
        ]);

        // Extract original order ID
        $originalOrderId = $this->extractOrderId($orderId);
        
        if ($originalOrderId) {
            return redirect()->route('user.order-detail', $originalOrderId)
                           ->with('warning', 'Payment was not completed. Please continue your payment.');
        }

        return redirect()->route('user.order-lists')
                       ->with('warning', 'Payment was not completed.');
    }
} 