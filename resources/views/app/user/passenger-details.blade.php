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
    
    <!-- SweetAlert2 (jika belum ada di layout) -->
    @if(!isset($__env->getSections()['sweetalert']))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endif
    
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
                        onSuccess: function(result) {
                            console.log('Payment success:', result);
                            
                            // Wait a moment for callback to process, then check status
                            setTimeout(() => {
                                if (window.orderResult && window.orderResult.order_id) {
                                    // Check payment status to ensure order is updated
                                    fetch('/api/orders/' + window.orderResult.order_id + '/check-status', {
                                        method: 'GET',
                                        headers: {
                                            'Accept': 'application/json',
                                            'X-CSRF-TOKEN': token
                                        }
                                    }).then(response => response.json())
                                    .then(statusData => {
                                        console.log('Payment status checked:', statusData);
                                        
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Pembayaran Berhasil!',
                                            text: 'Terima kasih atas pembayaran Anda.',
                                            confirmButtonText: 'Lihat Detail Pesanan',
                                            confirmButtonColor: '#28a745',
                                            allowOutsideClick: false
                                        }).then((swalResult) => {
                                            window.location.href = '/user/order-detail/' + window.orderResult.order_id;
                                        });
                                    }).catch(error => {
                                        console.error('Error checking status:', error);
                                        // Still show success and redirect
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Pembayaran Berhasil!',
                                            text: 'Terima kasih atas pembayaran Anda.',
                                            confirmButtonText: 'Lihat Detail Pesanan',
                                            confirmButtonColor: '#28a745',
                                            allowOutsideClick: false
                                        }).then((swalResult) => {
                                            window.location.href = '/user/order-detail/' + window.orderResult.order_id;
                                        });
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Pembayaran Berhasil!',
                                        text: 'Terima kasih atas pembayaran Anda.',
                                        confirmButtonText: 'Lihat Daftar Pesanan',
                                        confirmButtonColor: '#28a745',
                                        allowOutsideClick: false
                                    }).then((swalResult) => {
                                        window.location.href = '/user/order-lists';
                                    });
                                }
                            }, 2000); // Wait 2 seconds for callback processing
                        },
                        onPending: function(result) {
                            console.log('Payment pending:', result);
                            
                            Swal.fire({
                                icon: 'warning',
                                title: 'Pembayaran Pending',
                                text: 'Pembayaran Anda sedang diproses. Silakan selesaikan pembayaran sesuai instruksi.',
                                confirmButtonText: 'Lihat Instruksi Pembayaran',
                                confirmButtonColor: '#ffc107',
                                showCancelButton: true,
                                cancelButtonText: 'Kembali ke Daftar Pesanan',
                                cancelButtonColor: '#6c757d'
                            }).then((swalResult) => {
                                if (swalResult.isConfirmed && window.orderResult && window.orderResult.order_id) {
                                    window.location.href = '/user/order-detail/' + window.orderResult.order_id;
                                } else if (swalResult.dismiss === Swal.DismissReason.cancel) {
                                    window.location.href = '/user/order-lists';
                                }
                            });
                        },
                        onError: function(result) {
                            console.log('Payment error:', result);
                            
                            // Get detailed error message
                            let errorMessage = 'Pembayaran gagal diproses.';
                            if (result.status_message) {
                                errorMessage = result.status_message;
                            }
                            
                            Swal.fire({
                                icon: 'error',
                                title: 'Pembayaran Gagal!',
                                text: errorMessage,
                                confirmButtonText: 'Coba Lagi',
                                confirmButtonColor: '#dc3545',
                                showCancelButton: true,
                                cancelButtonText: 'Batalkan Pesanan',
                                cancelButtonColor: '#6c757d',
                                footer: '<small>Jika masalah berlanjut, hubungi customer service kami.</small>'
                            }).then((swalResult) => {
                                if (swalResult.isConfirmed) {
                                    // Reset form to retry
                                    submitButton.innerHTML = originalText;
                                    submitButton.disabled = false;
                                    messageDiv.innerHTML = '<div class="alert alert-info">Silakan coba lagi untuk melakukan pembayaran.</div>';
                                } else if (swalResult.dismiss === Swal.DismissReason.cancel) {
                                    // Cancel order and redirect
                                    if (window.orderResult && window.orderResult.order_id) {
                                        // Call API to cancel the order
                                        fetch('/api/orders/' + window.orderResult.order_id + '/cancel', {
                                            method: 'PUT',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'Accept': 'application/json',
                                                'X-CSRF-TOKEN': token
                                            }
                                        }).then(response => response.json())
                                        .then(data => {
                                            console.log('Order cancelled:', data);
                                        }).catch(error => {
                                            console.error('Error cancelling order:', error);
                                        });
                                        window.location.href = '/user/order-lists';
                                    } else {
                                        window.location.href = '/';
                                    }
                                }
                            });
                        },
                        onClose: function() {
                            console.log('Payment popup closed');
                            
                            Swal.fire({
                                icon: 'info',
                                title: 'Pembayaran Dibatalkan',
                                text: 'Anda menutup jendela pembayaran. Apakah Anda ingin melanjutkan pembayaran?',
                                confirmButtonText: 'Ya, Lanjutkan',
                                confirmButtonColor: '#3085d6',
                                showCancelButton: true,
                                cancelButtonText: 'Tidak, Batalkan',
                                cancelButtonColor: '#6c757d'
                            }).then((swalResult) => {
                                if (swalResult.isConfirmed) {
                                    // Reset button to allow retry
                                    submitButton.innerHTML = originalText;
                                    submitButton.disabled = false;
                                    messageDiv.innerHTML = '<div class="alert alert-warning">Silakan klik tombol untuk melanjutkan pembayaran.</div>';
                                } else {
                                    // Cancel order and redirect
                                    if (window.orderResult && window.orderResult.order_id) {
                                        // Call API to cancel the order
                                        fetch('/api/orders/' + window.orderResult.order_id + '/cancel', {
                                            method: 'PUT',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'Accept': 'application/json',
                                                'X-CSRF-TOKEN': token
                                            }
                                        }).then(response => response.json())
                                        .then(data => {
                                            console.log('Order cancelled:', data);
                                        }).catch(error => {
                                            console.error('Error cancelling order:', error);
                                        });
                                    }
                                    window.location.href = '/user/order-lists';
                                }
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Membuat Token Pembayaran',
                        text: result.message || 'Terjadi kesalahan saat memproses pembayaran.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc3545'
                    });
                }
            } catch (err) {
                console.error('Error:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Sistem',
                    text: 'Terjadi kesalahan saat mengirim data. Silakan coba lagi.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });
            } finally {
                // Reset button state
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        });
    </script>
@endsection
