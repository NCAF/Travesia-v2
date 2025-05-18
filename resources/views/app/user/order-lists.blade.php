@extends('layouts.main')
@section('title', 'Order Lists')
@push('styles')
    <style>
        .custom-card {
            background-color: #F2F5F7;
            border-radius: 24px;
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
        
        .status-canceled {
            background-color: #F8D7DA;
            color: #DC3545;
        }
        
        .status-finished {
            background-color: #D1ECF1;
            color: #17A2B8;
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
                    <div class="custom-card p-4">
                        <div class="row align-items-center">
                            <div class="row">
                                <div class="col-md-9">
                                    <h4 class="text-start">{{ $order->travel_name }}</h4>
                                </div>
                                <div class="col-md-3 text-end">
                                    <button class="status-btn status-{{ $order->status }} text-end">
                                        @if($order->status == 'order')
                                            Waiting for Payment
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
                                        @php
                                            $startTime = $order->start_date ? \Carbon\Carbon::parse($order->start_date)->format('H.i') . ' WIB' : '-';
                                        @endphp
                                        <p class="custom-txt">{{ $startTime }}</p>
                                    </div>
                                    <div class="col-md align-content-center">
                                        <img src="{{ asset('icons/icon-line-left.svg') }}" alt="line left">
                                    </div>
                                    <div class="col-md align-content-center">
                                        <p class="custom-txt">
                                            @if($order->start_date && isset($order->end_date))
                                                {{ \Carbon\Carbon::parse($order->start_date)->diff(\Carbon\Carbon::parse($order->end_date))->format('%hj %im') }}
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md align-content-center">
                                        <img src="{{ asset('icons/icon-line-right.svg') }}" alt="line right">
                                    </div>
                                    <div class="col-md">
                                        <p>{{ $order->end_point }}</p>
                                        @php
                                            $endTime = isset($order->end_date) ? \Carbon\Carbon::parse($order->end_date)->format('H.i') . ' WIB' : '-';
                                        @endphp
                                        <p class="custom-txt">{{ $endTime }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <h4 class="me-5">IDR {{ number_format($order->harga_kursi, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
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
