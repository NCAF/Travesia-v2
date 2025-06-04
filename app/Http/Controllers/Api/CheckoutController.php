<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use App\Traits\MidtransConfigTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class CheckoutController extends Controller
{
    use MidtransConfigTrait;
    public function process(Request $request)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'order_id' => 'required|string',
                'transaction_id' => 'nullable|string',
                'status' => 'required|string',
                'payment_method' => 'nullable|string',
                'gross_amount' => 'required|numeric|min:1000', // Midtrans minimum
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation failed: ' . $validator->errors()->first(),
                    'data' => null
                ], 400);
            }

            $data = $validator->validated();

            // Configure Midtrans using trait
            try {
                $this->configureMidtrans();
            } catch (\Exception $e) {
                return response()->json([
                    'error' => true,
                    'message' => $e->getMessage(),
                    'data' => null
                ], 500);
            }

            // Get user data with fallbacks
            $user = auth()->user();
            $customerName = !empty($user->nama) ? $user->nama : 'Customer';
            $customerEmail = !empty($user->email) ? $user->email : 'customer@travesia.com';

            // Generate unique order ID for Midtrans (max 50 characters)
            $orderIdForMidtrans = 'TXN-' . $data['order_id'] . '-' . time();

            // Prepare Midtrans parameters with complete data
            $params = array(
                'transaction_details' => array(
                    'order_id' => $orderIdForMidtrans,
                    'gross_amount' => (int) $data['gross_amount'],
                ),
                'customer_details' => array(
                    'first_name' => $customerName,
                    'email' => $customerEmail,
                ),
                'item_details' => array(
                    array(
                        'id' => 'TRAVEL-' . $data['order_id'],
                        'price' => (int) $data['gross_amount'],
                        'quantity' => 1,
                        'name' => 'Travel Booking Payment',
                    )
                )
            );

            // Log params for debugging (remove in production)
            \Log::info('Midtrans Checkout Params:', $params);

            // Generate Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Create transaction record
            $transaction = Transaction::create([
                'order_id' => $data['order_id'],
                'transaction_id' => $data['transaction_id'] ?? $orderIdForMidtrans,
                'status' => $data['status'],
                'payment_method' => $data['payment_method'] ?? 'midtrans',
                'gross_amount' => $data['gross_amount'],
                'snap_token' => $snapToken,
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'error' => false,
                    'message' => 'Payment token generated successfully',
                    'data' => [
                        'snap_token' => $snapToken,
                        'transaction_id' => $transaction->id,
                        'order_id' => $data['order_id']
                    ]
                ]);
            }

            return redirect()->route('order.detail', $transaction->order_id);

        } catch (\Midtrans\Exception $e) {
            \Log::error('Midtrans Error in CheckoutController: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Midtrans Error: ' . $e->getMessage(),
                    'data' => null
                ], 500);
            }
            
            return back()->with('error', 'Payment processing failed: ' . $e->getMessage());

        } catch (\Exception $e) {
            \Log::error('General Error in CheckoutController: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Failed to process payment: ' . $e->getMessage(),
                    'data' => null
                ], 500);
            }
            
            return back()->with('error', 'Payment processing failed. Please try again.');
        }
    }
}
