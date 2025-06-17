@extends('layouts.main')
@section('title', 'Order Lists')
@push('styles')
    <style>
        .custom-card {
            background-color: #F2F5F7;
            border-radius: 24px;
            display: block;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
        }

        .custom-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            text-decoration: none;
            color: inherit;
        }

        .status-btn {
            background-color: #EDE9D4;
            color: #BCAE62;
            border-radius: 999px;
            border: 1px solid #EDE9D4;
            padding: 5px 10px;
            font-size: 12px;
        }
        
        .status-order {
            background-color: #EDE9D4;
            color: #BCAE62;
        }
        
        .status-paid {
            background-color: #D4EDDA;
            color: #28A745;
        }
        
        .status-cancelled {
            background-color: #F8D7DA;
            color: #DC3545;
        }
        
        .status-finished {
            background-color: #D1ECF1;
            color: #17A2B8;
        }
        
        .status-pending_payment {
            background-color: #FFF3CD;
            color: #856404;
        }
        
        .status-failed {
            background-color: #F8D7DA;
            color: #DC3545;
        }
    </style>
@endpush
@section('content')
    <div class="container my-4">
        <h5 class="text-center my-5">Your Orders</h5>
        <div class="row">
            @if(isset($orders) && count($orders) > 0)
                @foreach($orders as $order)
                <div class="col-md-12 col-12 mb-2">
                    <a href="{{ route('user.order-detail', $order->id) }}" class="custom-card p-4">
                        <div class="row align-items-center">
                            <div class="row">
                                <div class="col-md-9">
                                    <h4 class="text-start">{{ $order->travel_name }}</h4>
                                </div>
                                <div class="col-md-3 text-end">
                                    <button class="status-btn status-{{ $order->status }} text-end">
                                        @if($order->status == 'finished')
                                            Selesai
                                        @elseif($order->status == 'pending_payment')
                                            Menunggu Pembayaran
                                        @elseif($order->status == 'paid')
                                            Dibayar
                                        @elseif($order->status == 'cancelled')
                                            Dibatalkan
                                        @elseif($order->status == 'failed')
                                            Gagal
                                        @else
                                            {{ ucfirst($order->status) }}
                                        @endif
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md">
                                        <p>{{ $order->check_point }}</p>
                                        <p class="text-muted">
                                                {{ \Carbon\Carbon::parse($order->start_date)->format('H.i') }}</p>
                                    </div>
                                    <div class="col-md align-content-center">
                                        <img src="{{ asset('icons/icon-line-left.svg') }}" alt="line left">
                                    </div>
                                    <div class="col-md align-content-center">
                                        <p class="custom-txt">
                                        @php
                                                    $start = \Carbon\Carbon::parse($order->start_date);
                                                    $end = \Carbon\Carbon::parse($order->end_date);
                                                    $diffInMinutes = $start->diffInMinutes($end);
                                                    $hours = floor($diffInMinutes / 60);
                                                    $minutes = $diffInMinutes % 60;
                                                @endphp
                                                {{ $hours > 0 ? $hours . ' jam ' : '' }}{{ $minutes > 0 ? $minutes . ' menit' : '0 menit' }}
                                        </p>
                                    </div>
                                    <div class="col-md align-content-center">
                                        <img src="{{ asset('icons/icon-line-right.svg') }}" alt="line right">
                                    </div>
                                    <div class="col-md">
                                            <p>{{ $order->end_point }}</p>
                                            <p class="text-muted">
                                                {{ \Carbon\Carbon::parse($order->end_date)->format('H.i') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <h4 class="me-5">IDR {{ number_format($order->harga_kursi, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            @else
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        Anda belum memiliki pesanan. <a href="{{ route('user.destination-list') }}">Cari destinasi</a> untuk membuat pesanan.
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
