<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Destinasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $data['destinasi'] = number_format(Destinasi::where('user_id', auth()->id())->count(), 0, '.', ',');

        $data['pesanan'] = number_format(Order::join('destinasi', 'destinasi.id', 'orders.destinasi_id')
                                            ->where('destinasi.user_id', auth()->id())
                                            ->where('orders.status', '!=', 'canceled')
                                            ->count(), 0, '.', ',');

        $data['total_pendapatan'] = number_format(Order::select(DB::raw('SUM(subtotal) as total'))
                                            ->join('destinasi', 'destinasi.id', 'orders.destinasi_id')
                                            ->where('destinasi.user_id', auth()->id())
                                            ->where('orders.status', '!=', 'canceled')
                                            ->first()->total, 0, '.', ',');

        $data['pendapatan_bulan_ini'] = number_format(Order::select(DB::raw('SUM(subtotal) as total'))
                                            ->join('destinasi', 'destinasi.id', 'orders.destinasi_id')
                                            ->where('destinasi.user_id', auth()->id())
                                            ->where('orders.status', '!=', 'canceled')
                                            ->whereRaw('MONTH(hari_berangkat) = ?', date('m'))
                                            ->whereRaw('YEAR(hari_berangkat) = ?', date('Y'))
                                            ->first()->total, 0, '.', ',');

        return response()->json([
            'error' => false,
            'message' => 'OK.',
            'data' => $data
        ]);
    }
}
