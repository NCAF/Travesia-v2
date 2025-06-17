<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Traits\MidtransConfigTrait;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use MidtransConfigTrait;
    public function seller(Request $request)
    {
        $order = Order::select(['*',
            'orders.status', 'orders.id',
            'orders.jumlah_kursi', 'orders.harga_kursi',
            'orders.jumlah_bagasi', 'orders.harga_bagasi'
        ])->join('destinasi', 'destinasi.id', 'orders.destinasi_id')
        ->join('users', 'users.id', 'orders.user_id');

        $user = Auth::user();
        $order = $order->where('destinasi.user_id', '=', $user->id);

        $search = isset($request->search['value']) ? $request->search['value'] : '';
        if (!empty($search)) {
            $order->where(function ($query) use ($search) {
                $query->Where('kode_destinasi', 'like', '%'.$search.'%')
                        ->orWhere('orders.status', 'like', '%'.$search.'%');
            });
        }

        $total_data = $order->count();
        $length = intval(isset($request->length) ? $request->length : 0);
        $start = intval(isset($request->start) ? $request->start : 0);

        $order = $order->orderBy('orders.id', 'DESC');

        if (!isset($request->length) || !isset($request->start)) {
            $order = $order->get();
        } else {
            $order = $order->skip($start)->take($length)->get();
        }

        return response()->json([
            'error' => false,
            'message' => 'Berhasil mengambil data.',
            'data' => $order,
            'draw' => $request->draw,
            'recordsTotal' => $total_data,
            'recordsFiltered' => $total_data,
        ], 200);
    }

    public function user(Request $request)
    {
        $order = Order::select(['*',
            'orders.status', 'orders.id',
            'orders.jumlah_kursi', 'orders.harga_kursi',
            'orders.jumlah_bagasi', 'orders.harga_bagasi', 'destinasi.foto'
        ])->join('destinasi', 'destinasi.id', 'orders.destinasi_id')
        ->join('users', 'users.id', 'orders.user_id');

        $user = Auth::user();
        $order = $order->where('orders.user_id', $user->id)
                      ->whereNotIn('orders.status', ['canceled']); // Exclude canceled orders

        $search = isset($request->search) ? $request->search : '';
        if (!empty($search)) {
            $order->where(function ($query) use ($search) {
                $query->Where('kode_destinasi', 'like', '%'.$search.'%')
                        ->orWhere('orders.status', 'like', '%'.$search.'%')
                        ->orWhere('destinasi.destinasi_awal', 'like', '%'.$search.'%')
                        ->orWhere('destinasi.destinasi_akhir', 'like', '%'.$search.'%');
            });
        }

        $total = $order->count();

        $length = intval(isset($request->length) ? $request->length : 3);
        $skip = intval(isset($request->page) ? ($request->page*$length) : 0);

        $order = $order->orderBy('orders.id', 'DESC')->skip($skip)->take($length)->get();

        return response()->json([
            'error' => false,
            'message' => 'Berhasil mengambil data.',
            'data' => $order,
            'length' => $length,
            'skip' => $skip,
            'end' => ($request->page*$length)+$length >= $total ? true : false,
        ], 200);
    }

    public function show(string $id)
    {
        $order = Order::select([
            'orders.*',
            'destinasi.kode_destinasi',
            'destinasi.destinasi_awal',
            'destinasi.destinasi_akhir',
            'destinasi.hari_berangkat',
        ])->join('destinasi', 'destinasi.id', 'orders.destinasi_id')
        ->where('orders.user_id', auth()->id())
        ->where('orders.order_id', $id)
        ->whereNotIn('orders.status', ['canceled']) // Exclude canceled orders
        ->first();

        if (!$order) {
            return response()->json([
                'error' => true,
                'message' => 'Pesanan tidak ditemukan.',
                'data' => null
            ], 200);
        }

        return response()->json([
            'error' => false,
            'message' => 'Berhasil mengambil data.',
            'data' => $order
        ], 200);
    }

    public function orderLists(string $id)
    {
        $order = Order::select([
            'orders.*',
            'destinasi.travel_name',
            'destinasi.check_point', 
            'destinasi.end_point',
            'destinasi.start_date',
            'destinasi.end_date',
            'destinasi.price',
            'destinasi.link_wa_group'
        ])->join('destinasi', 'destinasi.id', 'orders.destinasi_id')
        ->where('orders.id', $id)
        ->where('orders.user_id', auth()->id())
        ->whereIn('orders.status', ['order', 'finished']) // Only show order and finished status
        ->first();
        
        // dd($order);
        if (!$order) {
            return redirect()->route('user.order-lists')->with('error', 'Order not found');
        }
        
        return view('app.user.order-detail', compact('order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'destinasi_id' => 'required|exists:destinasi,id',
            'jumlah_kursi' => 'required|integer|min:1',
            'harga_kursi' => 'required|numeric',
            'existing_order_id' => 'nullable|integer|exists:orders,id',
        ]);

        // Check if this is continuing an existing order
        if (isset($validated['existing_order_id'])) {
            $order = Order::where('id', $validated['existing_order_id'])
                         ->where('user_id', Auth::id())
                         ->first();
                         
            if (!$order) {
                return response()->json([
                    'error' => true,
                    'message' => 'Order not found',
                    'data' => null
                ]);
            }
        } else {
            // Create new order
            $order = Order::create([
                'user_id' => Auth::id(),
                'destinasi_id' => $validated['destinasi_id'],
                'jumlah_kursi' => $validated['jumlah_kursi'],
                'harga_kursi' => $validated['harga_kursi'],
                'status' => 'order',
                'order_id' => Str::uuid(),
            ]);
        }

        if ($order) {
            if ($request->wantsJson()) {
                try {
                    // Configure Midtrans using trait
                    $this->configureMidtrans();

                    // Calculate total amount - ensure it's integer and at least 1000 (Midtrans minimum)
                    $hargaKursi = (int) $validated['harga_kursi'];
                    $jumlahKursi = (int) $validated['jumlah_kursi'];
                    $totalAmount = $hargaKursi * $jumlahKursi;
                    
                    // Midtrans minimum amount is 1000
                    if ($totalAmount < 1000) {
                        // Delete the order if amount is invalid
                        $order->delete();
                        return response()->json([
                            'error' => true,
                            'message' => 'Minimum payment amount is IDR 1.000',
                            'data' => null
                        ]);
                    }

                    // Generate unique order ID (max 50 characters for Midtrans)
                    $orderIdForMidtrans = 'ORDER-' . $order->id . '-' . time();

                    // Get user data with fallbacks
                    $user = Auth::user();
                    $customerName = !empty($user->nama) ? $user->nama : 'Customer';
                    $customerEmail = !empty($user->email) ? $user->email : 'customer@travesia.com';

                    // Prepare Midtrans parameters
                    $params = array(
                        'transaction_details' => array(
                            'order_id' => $orderIdForMidtrans,
                            'gross_amount' => $totalAmount,
                        ),
                        'customer_details' => array(
                            'first_name' => $customerName,
                            'email' => $customerEmail,
                        ),
                        'item_details' => array(
                            array(
                                'id' => 'SEAT-' . $order->destinasi_id,
                                'price' => $hargaKursi,
                                'quantity' => $jumlahKursi,
                                'name' => 'Travel Ticket',
                            )
                        )
                    );

                    // Log params for debugging (remove in production)
                    \Log::info('Midtrans Params:', $params);

                    // Generate Snap Token
                    $snapToken = \Midtrans\Snap::getSnapToken($params);

                    return response()->json([
                        'error' => false,
                        'message' => 'Order created successfully',
                        'snapToken' => $snapToken,
                        'order_id' => $order->id
                    ]);
                } catch (\Midtrans\Exception $e) {
                    // Delete the order if Midtrans fails
                    $order->delete();
                    \Log::error('Midtrans Error: ' . $e->getMessage());
                    return response()->json([
                        'error' => true,
                        'message' => 'Midtrans Error: ' . $e->getMessage(),
                        'data' => null
                    ]);
                } catch (\Exception $e) {
                    // Delete the order if general error occurs
                    $order->delete();
                    \Log::error('General Error: ' . $e->getMessage());
                    return response()->json([
                        'error' => true,
                        'message' => 'Failed to create payment token: ' . $e->getMessage(),
                        'data' => null
                    ]);
                }
            }
            return redirect()->route('user.order-lists')->with('success', 'Booking berhasil!');
        } else {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Booking gagal!',
                    'data' => null
                ]);
            }
            return back()->with('error', 'Booking gagal!');
        }
    }

    /**
     * Cancel order when payment popup is closed
     */
    public function cancelOrder(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,id'
        ]);

        $order = Order::where('id', $validated['order_id'])
                     ->where('user_id', Auth::id())
                     ->where('status', 'order')
                     ->first();

        if ($order) {
            $order->update(['status' => 'canceled']);
            return response()->json([
                'error' => false,
                'message' => 'Order cancelled successfully'
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => 'Order not found or cannot be cancelled'
        ]);
    }

    /**
     * Update order status to finished when payment is successful
     */
    public function finishOrder(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,id'
        ]);

        $order = Order::where('id', $validated['order_id'])
                     ->where('user_id', Auth::id())
                     ->where('status', 'order')
                     ->first();

        if ($order) {
            $order->update(['status' => 'finished']);
            \Log::info('Order #' . $order->id . ' status updated to finished for user #' . Auth::id());
            
            return response()->json([
                'error' => false,
                'message' => 'Order completed successfully',
                'order_status' => 'finished'
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => 'Order not found or cannot be completed'
        ]);
    }

    public function userOrderList()
    {
        $userId = auth()->id();
        $orders = Order::select([
            'orders.*',
            'destinasi.travel_name', 
            'destinasi.check_point',
            'destinasi.end_point',
            'destinasi.start_date',
            'destinasi.price'
        ])
        ->join('destinasi', 'destinasi.id', 'orders.destinasi_id')
        ->where('orders.user_id', $userId)
        ->whereIn('orders.status', ['order', 'finished']) // Show order and finished status
        ->orderBy('orders.created_at', 'desc')
        ->get();

        // dd($orders);
        return view('app.user.order-lists', compact('orders'));
    }
}
