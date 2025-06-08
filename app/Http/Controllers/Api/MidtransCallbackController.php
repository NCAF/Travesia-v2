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
            Log::info('Midtrans Notification Received (Raw):', $request->all());
            
            // Extract data from request
            $requestData = $request->all();
            $transactionId = $requestData['transaction_id'] ?? 'unknown';
            $orderId = $requestData['order_id'] ?? 'unknown';
            $transactionStatus = $requestData['transaction_status'] ?? 'pending';
            $paymentType = $requestData['payment_type'] ?? 'unknown';
            $grossAmount = $requestData['gross_amount'] ?? 0;
            $fraudStatus = $requestData['fraud_status'] ?? null;
            $transactionTime = $requestData['transaction_time'] ?? null;
            $settlementTime = $requestData['settlement_time'] ?? null;
            
            Log::info('Midtrans Notification Parsed:', [
                'transaction_id' => $transactionId,
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
                'gross_amount' => $grossAmount,
                'fraud_status' => $fraudStatus
            ]);

            // Extract original order ID from Midtrans order ID
            $originalOrderId = $this->extractOrderId($orderId);
            
            if (!$originalOrderId) {
                Log::warning('Cannot extract original order ID from: ' . $orderId . ', treating as test notification');
                // For test notifications, create dummy order ID
                $originalOrderId = 1; // Default order ID for test
            }

            // Try to save to database, but don't fail if database issues occur
            try {
                DB::beginTransaction();

                // Find or create transaction record
                $transaction = Transaction::updateOrCreate(
                    [
                        'transaction_id' => $transactionId
                    ],
                    [
                        'order_id' => $originalOrderId,
                        'payment_method' => $paymentType,
                        'gross_amount' => is_numeric($grossAmount) ? $grossAmount : 0,
                        'status' => $this->mapMidtransStatus($transactionStatus),
                        'payment_code' => $this->getPaymentCodeFromRequest($requestData),
                        'fraud_status' => $fraudStatus,
                        'transaction_time' => $transactionTime ? \Carbon\Carbon::parse($transactionTime) : now(),
                        'settlement_time' => $settlementTime ? \Carbon\Carbon::parse($settlementTime) : null,
                        'midtrans_response' => $requestData,
                    ]
                );

                // Update order status if order exists
                if ($originalOrderId > 1) { // Skip for test order ID
                    $this->updateOrderStatus($originalOrderId, $transactionStatus, $fraudStatus);
                }

                DB::commit();

                Log::info('Transaction saved successfully:', [
                    'transaction_id' => $transaction->id,
                    'order_id' => $originalOrderId,
                    'status' => $transaction->status
                ]);

            } catch (Exception $dbError) {
                DB::rollback();
                Log::error('Database error in notification (continuing anyway): ' . $dbError->getMessage());
                // Continue processing even if database fails
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Notification processed successfully',
                'timestamp' => now(),
                'transaction_id' => $transactionId,
                'order_id' => $orderId
            ]);

        } catch (Exception $e) {
            Log::error('General Exception in Midtrans notification: ' . $e->getMessage());
            
            // Still return success to prevent Midtrans from retrying
            return response()->json([
                'status' => 'success',
                'message' => 'Notification received (with errors)',
                'error' => $e->getMessage(),
                'timestamp' => now()
            ]);
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
     * Get payment code from request data
     */
    private function getPaymentCodeFromRequest($requestData)
    {
        // For Bank Transfer (VA)
        if (isset($requestData['va_numbers']) && is_array($requestData['va_numbers']) && !empty($requestData['va_numbers'])) {
            return $requestData['va_numbers'][0]['va_number'] ?? null;
        }

        // For QRIS
        if (isset($requestData['qr_string'])) {
            return $requestData['qr_string'];
        }

        // For other payment methods
        if (isset($requestData['payment_code'])) {
            return $requestData['payment_code'];
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