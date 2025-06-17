@extends('layouts.main')
@section('title', 'Passenger Details Page')
@push('styles')
    <style>
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
        <div class="row mt-5">
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
                        <img src="{{ asset('icons/icon-line-left.svg') }}" alt="line left" style="width: 22%"
                            class="ms-2">
                        <div class="text-center">
                            <small>
                                @if ($destinasi->start_date && $destinasi->end_date)
                                    {{ \Carbon\Carbon::parse($destinasi->start_date)->diff(\Carbon\Carbon::parse($destinasi->end_date))->format('%hj %im') }}
                                @else
                                    -
                                @endif
                            </small>
                        </div>
                        <img src="{{ asset('icons/icon-line-right.svg') }}" alt="line right" style="width: 22%"
                            class="me-2">
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
                        <input type="hidden" name="destinasi_id"
                            value="{{ request('destinasi_id', $destinasi->id ?? '') }}">
                        <input type="hidden" name="jumlah_kursi" value="1">
                        <input type="hidden" name="harga_kursi" value="{{ $destinasi->price ?? 0 }}">
                        <button type="submit" class="btn custom-btn w-100 fw-bold">Continue Payment</button>
                    </form>
                    <div id="orderMessage" class="mt-2"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Midtrans Snap Script -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    
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
            
            // Show loading state
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = 'Processing...';
            submitButton.disabled = true;
            
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
                
                if (!result.error && result.snapToken) {
                    // Store order result for later use
                    window.orderResult = result;
                    
                    // Show Midtrans popup
                    snap.pay(result.snapToken, {
                        onSuccess: async function(result) {
                            messageDiv.innerHTML = '<div class="alert alert-success">Payment successful! Thank you for your order.</div>';
                            console.log('Payment success:', result);
                            
                            // Update order status to finished
                            try {
                                console.log('Updating order status to finished for order_id:', window.orderResult.order_id);
                                const finishResponse = await fetch('/orders/finish', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': token
                                    },
                                    body: JSON.stringify({
                                        order_id: window.orderResult.order_id
                                    })
                                });
                                
                                console.log('Finish response status:', finishResponse.status);
                                console.log('Finish response headers:', finishResponse.headers);
                                
                                if (!finishResponse.ok) {
                                    const errorText = await finishResponse.text();
                                    console.error('HTTP Error:', finishResponse.status, errorText);
                                    return;
                                }
                                
                                const finishResult = await finishResponse.json();
                                console.log('Finish order response:', finishResult);
                                
                                if (finishResult.error) {
                                    console.error('Failed to finish order:', finishResult.message);
                                } else {
                                    console.log('Order successfully finished!');
                                }
                            } catch (err) {
                                console.error('Failed to update order status:', err);
                                console.error('Error details:', err.stack);
                            }
                            
                            // Redirect to order detail page
                            setTimeout(() => {
                                if (window.orderResult && window.orderResult.order_id) {
                                    window.location.href = '/user/order-detail/' + window.orderResult.order_id;
                                } else {
                                    window.location.href = '/user/order-lists';
                                }
                            }, 2000);
                        },
                        onPending: function(result) {
                            messageDiv.innerHTML = '<div class="alert alert-warning">Payment is pending. Please complete your payment.</div>';
                            console.log('Payment pending:', result);
                        },
                        onError: async function(result) {
                            messageDiv.innerHTML = '<div class="alert alert-danger">Payment failed. Please try again.</div>';
                            console.log('Payment error:', result);
                            
                            // Cancel the order when payment fails
                            try {
                                await fetch('/orders/cancel', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': token
                                    },
                                    body: JSON.stringify({
                                        order_id: window.orderResult.order_id
                                    })
                                });
                            } catch (err) {
                                console.error('Failed to cancel order:', err);
                            }
                        },
                        onClose: function() {
                            messageDiv.innerHTML = '<div class="alert alert-warning">Pembayaran anda batal.</div>';
                            console.log('Payment popup closed');
                            
                            // Don't cancel the order, just redirect to order detail
                            // Order remains with status 'order' so user can complete payment later
                            setTimeout(() => {
                                if (window.orderResult && window.orderResult.order_id) {
                                    window.location.href = '/user/order-detail/' + window.orderResult.order_id;
                                } else {
                                    window.location.href = '/user/order-lists';
                                }
                            }, 2000);
                        }
                    });
                } else {
                    messageDiv.innerHTML = '<div class="alert alert-danger">' + (result.message || 'Failed to create payment token') + '</div>';
                }
            } catch (err) {
                messageDiv.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat mengirim data.</div>';
                console.error('Error:', err);
            } finally {
                // Reset button state
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        });
    </script>
@endsection
