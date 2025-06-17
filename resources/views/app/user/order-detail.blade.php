@extends('layouts.main')
@section('title', 'Passenger Details Page')
@push('styles')
    <style>
        .form-check-input:checked {
            background-color: #a3a3a3 !important;
            border-color: #a3a3a3 !important;
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

        <div class="row">
            <!-- Left Column -->
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-1">
                        <a href="{{ route('user.order-lists') }}">
                            <img src="{{ asset('icons/arrow-left.svg') }}" alt="arrow left">
                        </a>
                    </div>
                    <div class="col-md-2">
                        <p>Back</p>
                    </div>
                </div>
                <h5 class="section-title">Booking Details</h5>
                <div class="info-card">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Name</label>
                            <p class="mb-0">{{ auth()->user()->nama ?? 'Muhammad Adib Firmansyah' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Email</label>
                            <p class="mb-0">{{ auth()->user()->email ?? 'adib@gmail.com' }}</p>
                        </div>
                    </div>
                </div>

                <h5 class="section-title mt-4">Passenger Details</h5>
                <div class="info-card">
                    <div class="passenger-item">
                        <div class="passenger-number">01</div>
                        <div>
                            <p class="mb-0">{{ auth()->user()->nama ?? 'Muhammad Adib Firmansyah' }}</p>
                            <small class="text-muted">{{ auth()->user()->email ?? 'adib@gmail.com' }}</small>
                        </div>
                    </div>
                </div>


                <h5 class="section-title">WA Contact</h5>
                {{-- <div class="info-card">
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
                </div> --}}
                <div class="info-card">
                    <div class="row">
                        <div class="col-md-6 d-flex align-items-center">
                            <a href="{{ $order->link_wa_group }}" target="_blank">
                               {{ $order->link_wa_group}}
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column -->
            <div class="col-md-4">
                <h5 class="section-title">Order Details</h5>
                <div class="info-card">
                    <h4>{{ $order->travel_name ?? 'Travel Service' }}</h4>
                        <div class="row mt-3 mb-2">
                            <div class="col-6">
                                <p>{{ $order->check_point ?? 'Origin' }}</p>
                            </div>
                            <div class="col-6 text-end">
                                <p>{{ $order->end_point ?? 'Destination' }}</p>
                            </div>
                        </div>
                        <div class="route-info">
                            <div>
                                <div class="time-info">
                                    {{ $order->start_date ? \Carbon\Carbon::parse($order->start_date)->format('H.i') . ' WIB' : '-' }}
                                </div>
                            </div>
                            <img src="{{ asset('icons/icon-line-left.svg') }}" alt="line left" style="width: 22%"
                                class="ms-2">
                            <div class="text-center">
                                <small>
                                    @if ($order->start_date && $order->end_date)
                                        {{ \Carbon\Carbon::parse($order->start_date)->diff(\Carbon\Carbon::parse($order->end_date))->format('%hj %im') }}
                                    @else
                                        -
                                    @endif
                                </small>
                            </div>
                            <img src="{{ asset('icons/icon-line-right.svg') }}" alt="line right" style="width: 22%"
                                class="me-2">
                            <div>
                                <div class="time-info">
                                    {{ $order->end_date ? \Carbon\Carbon::parse($order->end_date)->format('H.i') . ' WIB' : '-' }}
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <p class="custom-txt">Total</p>
                            <p class="price-info">IDR {{ number_format(($order->harga_kursi ?? 0) * ($order->jumlah_kursi ?? 1), 0, ',', '.') }}</p>
                        </div>

                        <div class="text-center">
                            @if($order->status == 'finished' || $order->status == 'completed')
                                <div class="alert alert-success">
                                    <h6>Status Pesanan</h6>
                                    <p class="mb-0">Order ID: {{ $order->order_id ?? 'N/A' }}</p>
                                    <small>Status: Finished</small>
                                </div>
                            @elseif($order->status == 'pending' || $order->status == 'pending_payment')
                                <div class="alert alert-warning">
                                    <h6>Menunggu Pembayaran</h6>
                                    <p class="mb-0">Order ID: {{ $order->order_id ?? 'N/A' }}</p>
                                    <small>Pembayaran sedang diproses. Halaman akan otomatis refresh.</small>
                                    <div class="mt-3">
                                        <div class="spinner-border spinner-border-sm text-warning" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <small class="ms-2">Menunggu konfirmasi pembayaran...</small>
                                    </div>
                                </div>
                                
                                <script>
                                    // Auto refresh halaman setiap 5 detik untuk cek status terbaru
                                    let refreshCount = 0;
                                    const maxRefresh = 12; // Maksimal 1 menit (12 x 5 detik)
                                    
                                    const interval = setInterval(() => {
                                        refreshCount++;
                                        if (refreshCount >= maxRefresh) {
                                            clearInterval(interval);
                                            return;
                                        }
                                        
                                        // Check status via API
                                        fetch('/api/orders/{{ $order->id }}/check-status', {
                                            method: 'GET',
                                            headers: {
                                                'Accept': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            }
                                        }).then(response => response.json())
                                        .then(data => {
                                            if (!data.error && data.data.status !== 'pending') {
                                                clearInterval(interval);
                                                location.reload(); // Refresh halaman jika status berubah
                                            }
                                        }).catch(error => {
                                            console.error('Error checking status:', error);
                                        });
                                    }, 5000);
                                </script>
                            @elseif($order->status == 'cancelled' || $order->status == 'failed')
                                <div class="alert alert-danger">
                                    <h6>Pembayaran Gagal</h6>
                                    <p class="mb-0">Order ID: {{ $order->order_id ?? 'N/A' }}</p>
                                    <small>{{ session('error') ?? 'Pembayaran tidak berhasil diproses' }}</small>
                                    <div class="mt-3">
                                        <a href="{{ route('user.destination-list') }}" class="btn btn-primary btn-sm">
                                            Buat Pesanan Baru
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <h6>Status Pesanan</h6>
                                    <p class="mb-0">Order ID: {{ $order->order_id ?? 'N/A' }}</p>
                                    <small>Status: {{ ucfirst($order->status ?? 'unknown') }}</small>
                                </div>
                            @endif
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
