@extends('layouts.main')
@section('title', 'Passenger Details Page')
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
        }
    </style>
@endpush
@section('content')
    <div class="container my-4">
        <h5 class="text-center my-5">Your Orders</h5>
        <div class="row">
            <div class="col-md-12 col-12 mb-2">
                <div class="custom-card p-4">
                    <div class="row align-items-center">
                        <div class="row">
                            <div class="col-md-9">
                                <h4 class="text-start">Madenpa 80</h4>
                            </div>
                            <div class="col-md-3 text-end">
                                <button class="status-btn text-end">Waiting for Payment</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md">
                                    <p>Malang</p>
                                    <p class="custom-txt">12.30 WIB</p>
                                </div>
                                <div class="col-md align-content-center">
                                    <img src="{{ asset('icons/icon-line-left.svg') }}" alt="line left">
                                </div>
                                <div class="col-md align-content-center">
                                    <p class="custom-txt"> 11j 32m </p>
                                </div>
                                <div class="col-md align-content-center">
                                    <img src="{{ asset('icons/icon-line-right.svg') }}" alt="line right">
                                </div>
                                <div class="col-md">
                                    <p>Denpasar</p>
                                    <p class="custom-txt">01.30 WIB</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <h4 class="me-5">IDR 300.000</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
