<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
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
        $order = $order->where('orders.user_id', $user->id);

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
        ->where('orders.user_id', auth()->id())->where('orders.order_id', $id)->first();

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function finished(string $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'error' => true,
                'message' => 'Pesanan tidak ditemukan.',
                'data' => null
            ], 200);
        }

        $order->update([
            'status' => 'finished',
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Pesanan berhasil diselesaikan.',
            'data' => null
        ]);
    }

    public function pay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required|in:paid,canceled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => Str::ucfirst($validator->errors()->first()),
                'data' => null
            ]);
        }

        $order = Order::where('order_id', $request->id)->first();

        if (!$order) {
            return response()->json([
                'error' => true,
                'message' => 'Order tidak ditemukan.',
                'data' => null
            ], 200);
        }

        $order->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Order berhasil diupdate.',
            'data' => null
        ]);
    }
}
