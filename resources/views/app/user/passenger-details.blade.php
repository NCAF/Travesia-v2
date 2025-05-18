@extends('layouts.main')
@section('title', 'Passenger Details Page')
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
                <div class="step">
                    <span>2</span>
                </div>
                <div class="step-label">Select Payment Method</div>
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
                <h5 class="section-title">Booking Details</h5>
                <div class="info-card">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Name</label>
                            <p class="mb-0">{{ auth()->user()->nama }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Email</label>
                            <p class="mb-0">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>

                <h5 class="section-title mt-4">Passenger Details</h5>
                <div class="info-card">
                    <div class="passenger-item">
                        <div class="passenger-number">01</div>
                        <div>
                            <p class="mb-0">{{ auth()->user()->nama }}</p>
                            <small class="text-muted">{{ auth()->user()->email }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-4">
                <h5 class="section-title">Order Details</h5>
                <div class="info-card">
                    <h4>{{ $destinasi->travel_name }}</h4>
                    <div class="row mt-3 mb-2">
                        <div class="col-6">
                            <p>{{ $destinasi->check_point }}</p>
                        </div>
                        <div class="col-6 text-end">
                            <p>{{ $destinasi->end_point }}</p>
                        </div>
                    </div>
                    <div class="route-info">
                        <div>
                            <div class="time-info">
                                {{ $destinasi->start_date ? \Carbon\Carbon::parse($destinasi->start_date)->format('H.i') . ' WIB' : '-' }}
                            </div>
                        </div>
                        <img src="{{ asset('icons/icon-line-left.svg') }}" alt="line left" style="width: 22%" class="ms-2">
                        <div class="text-center">
                            <small>
                                @if($destinasi->start_date && $destinasi->end_date)
                                    {{ \Carbon\Carbon::parse($destinasi->start_date)->diff(\Carbon\Carbon::parse($destinasi->end_date))->format('%hj %im') }}
                                @else
                                    -
                                @endif
                            </small>
                        </div>
                        <img src="{{ asset('icons/icon-line-right.svg') }}" alt="line right" style="width: 22%" class="me-2">
                        <div>
                            <div class="time-info">
                                {{ $destinasi->end_date ? \Carbon\Carbon::parse($destinasi->end_date)->format('H.i') . ' WIB' : '-' }}
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="custom-txt">Total</p>
                        <p class="price-info">IDR {{ number_format($destinasi->price, 0, ',', '.') }}</p>
                    </div>
                    <form id="orderForm">
                        @csrf
                        <input type="hidden" name="destinasi_id" value="{{ request('destinasi_id', $destinasi->id ?? '') }}">
                        <input type="hidden" name="jumlah_kursi" value="1">
                        <input type="hidden" name="harga_kursi" value="{{ $destinasi->price ?? 0 }}">
                        <button type="submit" class="btn custom-btn w-100 fw-bold">Continue</button>
                    </form>
                    <div id="orderMessage" class="mt-2"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('orderForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = e.target;
            const data = {
                destinasi_id: form.destinasi_id.value,
                jumlah_kursi: form.jumlah_kursi.value,
                harga_kursi: form.harga_kursi.value
            };
            const token = document.querySelector('input[name="_token"]').value;
            let messageDiv = document.getElementById('orderMessage');
            messageDiv.innerHTML = '';
            try {
                const response = await fetch('/api/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                if (!result.error) {
                    messageDiv.innerHTML = '<div class="alert alert-success">' + result.message + '</div>';
                    // Optional: redirect or update UI
                } else {
                    messageDiv.innerHTML = '<div class="alert alert-danger">' + result.message + '</div>';
                }
            } catch (err) {
                messageDiv.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat mengirim data.</div>';
            }
        });
    </script>
@endsection
