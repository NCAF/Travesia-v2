@extends('layouts.main')
@section('title', 'Select Payment Method Page')
@push('styles')
    <style>
        .steps-container {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
        }

        .step-item {
            display: flex;
            align-items: center;
            position: relative;
            margin: 0 10px;
        }

        .step-line {
            height: 2px;
            background-color: #a3a3a3;
            flex: 1;
            margin: 0 15px;
            min-width: 100px;
            /* tambahkan agar garis tetap tampak */
        }


        .step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #a3a3a3;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            flex-shrink: 0;
            color: white
        }

        .step.active {
            background-color: #3c8ee1;
            color: white;
        }

        .step-active-txt {
            font-size: 14px;
            color: #3c8ee1;
            white-space: nowrap;
        }

        .step-label {
            font-size: 14px;
            color: #a3a3a3;
            white-space: nowrap;
        }

        .step-line {
            height: 2px;
            background-color: #a3a3a3;
            flex-grow: 1;
            margin: 0 15px;
        }

        .info-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            border: #E0E0E0 1px solid;
            margin-bottom: 20px;
        }

        .passenger-item {
            display: flex;
            align-items: center;
        }

        .passenger-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-weight: bold;
        }


        .route-info {
            display: flex;
            align-items: center;
            margin-top: -26px;
        }



        .route-line {
            flex-grow: 1;
            height: 2px;
            background-color: #a3a3a3;
            margin: 0 10px;
        }

        .time-info {
            font-size: 12px;
            color: #a3a3a3;
        }

        .price-info {
            font-weight: bold;
        }

        .section-title {
            margin-bottom: 15px;
            font-weight: 600;
        }
    </style>
@endpush
@section('content')
    <div class="container my-4">
        <!-- Steps Indicator -->
        <div class="steps-container mb-5r">
            <div class="step-item">
                <div class="step active">
                    <span>1</span>
                </div>
                <div class="step-active-txt fw-bold">Passenger Details</div>
                <div class="step-line"></div>
            </div>

            <div class="step-item">
                <div class="step active">
                    <span>2</span>
                </div>
                <div class="step-active-txt fw-bold">Select Payment Method</div>
                <div class="step-line"></div>
            </div>
            <div class="step-item">
                <div class="step">
                    <span>3</span>
                </div>
                <div class="step-label">Payment</div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column -->
            <div class="col-md-8">
                <h5 class="section-title">Payment Method</h5>
                <div class="info-card">
                    <div class="row">
                        <div class="col-md-1">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault"
                                    id="flexRadioDefault1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <img src="{{ asset('icons/icon-qris.svg') }}" alt="QRIS">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-4">
                <h5 class="section-title">Order Details</h5>
                <div class="info-card">
                    <h4>Madenpa 80</h3>
                        <div class="row mt-3 mb-2">
                            <div class="col-6">
                                <p>Malang</p>
                            </div>
                            <div class="col-6 text-end">
                                <p>Denpasar</p>
                            </div>
                        </div>
                        <div class="route-info">
                            <div>
                                <div class="time-info">12.30 WIB</div>
                            </div>
                            <img src="{{ asset('icons/icon-line-left.svg') }}" alt="line left" style="width: 22%"
                                class="ms-2">
                            <div class="text-center">
                                <small>1j 32m</small>
                            </div>
                            <img src="{{ asset('icons/icon-line-right.svg') }}" alt="line right" style="width: 22%"
                                class="me-2">
                            <div>
                                <div class="time-info">01.30 WIB</div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <p class="custom-txt">Total</p>
                            <p class="price-info">IDR 300.000</p>
                        </div>

                        <button class="btn custom-btn w-100 fw-bold">Pay</button>
                </div>
            </div>
        </div>
    </div>
@endsection
