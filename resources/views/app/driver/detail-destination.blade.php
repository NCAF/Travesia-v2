@extends('layouts.app')
@section('title', 'Detail Destination Page')
@push('styles')
    <style>
        .sidebar {
            background-color: #F2F5F7;
            width: 250px;
            height: 100vh;
            position: fixed;
            padding: 20px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 12px;
            text-decoration: none;
            color: black;
            font-weight: 500;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .sidebar a img {
            margin-right: 10px;
        }

        .sidebar a.active,
        .sidebar a:hover {
            background: #3C8EE1;
            color: white;
        }

        .main-content {
            margin-left: 270px;
            padding: 20px;
        }

        .custom-card {
            background-color: #F2F5F7;
            border-radius: 24px;
        }

        .status-btn {
            background-color: #D4EDDB;
            color: #62BC72;
            border-radius: 999px;
            border: 1px solid #D4EDDB
        }

        .offcanvas-body a {
            display: flex;
            align-items: center;
            padding: 12px;
            text-decoration: none;
            color: black !important;
            font-weight: 500;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .offcanvas-body a img {
            margin-right: 10px;
        }

        .offcanvas-body a.active,
        .offcanvas-body a:hover {
            background: #3C8EE1;
            color: white !important;
        }


        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }
        }

        .drag-drop {
            border: none;
            padding: 0;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: auto;
            cursor: pointer;
            position: relative;
            background-color: transparent;
        }

        .drag-drop img {
            width: 100%;
            max-width: 300px;
            height: auto;
            border-radius: 10px;
        }

        .drag-drop input {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex">
        <!-- Tombol Menu untuk Mobile -->
        <button class="btn custom-btn d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
            ☰ Menu
        </button>

        <!-- Sidebar (Offcanvas untuk Mobile) -->
        <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarMenu">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <a href="{{ route('driver.destination-list') }}" class="mt-5 active"><img
                        src="{{ asset('icons/icon-destination.svg') }}" alt="Icon Destination"> Destination</a>
                <a href="{{ route('driver.order-list') }}"><img
                        src="{{ asset('icons/icon-order.svg') }}" alt="Icon Order"> Order</a>
                <a href="{{ route('logout') }}"><img src="{{ asset('icons/icon-logout.svg') }}" alt="Icon Logout">
                    Logout</a>
            </div>
        </div>

        <!-- Sidebar untuk Desktop -->
        <div class="sidebar d-none d-md-block">
            <img src="{{ asset('img/travesia.png') }}" alt="Logo Travesia" width="156" height="33">
            <div class="d-flex flex-column h-100">
                <div>
                    <a href="{{ route('driver.destination-list') }}" class="mt-5 active"><img
                        src="{{ asset('icons/icon-destination.svg') }}" alt="Icon Destination"> Destination</a>
                    <a href="{{ route('driver.order-list') }}"><img
                        src="{{ asset('icons/icon-order.svg') }}" alt="Icon Order"> Order</a>
                </div>
                <div class="mt-auto mb-4">
                    <a href="{{ route('logout') }}"><img src="{{ asset('icons/icon-logout.svg') }}" alt="Icon Logout"> Logout</a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content container">
            <h1>Destination</h1>
            <p class="custom-txt">Manage your travel destinations easily.</p>
            <div class="row mb-4">
                <div class="col-md-8 mt-3">
                    <h4>Detail Destination</h4>
                </div>
                <div class="col-md-auto text-end mt-3">
                    <a href="{{ route('driver.delete-destination', ['id' => $destinasi->id]) }}"
                        class="custom-btn-outline btn fw-bold"
                        onclick="return confirmDelete(event)">Delete</a>
                </div>
                <div class="col-md-auto text-end mt-3">
                    <a href="{{ route('driver.update-destination', ['id' => $destinasi->id]) }}"
                        class="custom-btn btn fw-bold">Edit Destination</a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="d-flex">
                        <p>General Information</p>
                        <hr class="flex-grow-1 ms-2">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label custom-txt">Travel Name</label>
                                <p>{{ $destinasi->travel_name }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label custom-txt">Start Date</label>
                                <p>{{ $destinasi->start_date }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label custom-txt">End Date</label>
                                <p>{{ $destinasi->end_date }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label custom-txt">Check Point</label>
                                <p>{{ $destinasi->check_point }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label custom-txt">End Point</label>
                                <p>{{ $destinasi->end_point }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label custom-txt">Description</label>
                            <p>{{ $destinasi->deskripsi }}</p>
                        </div>
                    </div>
                    <div class="d-flex mt-5">
                        <p>Vehicle Information</p>
                        <hr class="flex-grow-1 ms-2">
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label custom-txt">Vehicle Type</label>
                                <p>{{ $destinasi->vehicle_type }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label custom-txt">Plate Number</label>
                                <p>{{ $destinasi->plate_number }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label custom-txt">Number of Seats</label>
                                <p>{{ $destinasi->number_of_seats }} Seats</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Upload Image</label>
                        <div class="drag-drop">
                            @if ($destinasi->foto)
                                <img src="{{ asset('images/' . $destinasi->foto) }}" alt="Destination Image" height="200"
                                    width="300">
                            @else
                                <img src="{{ asset('img/detail-destination.png') }}" alt="Detail Destination">
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label custom-txt">Price</label>
                        <p>IDR {{ number_format($destinasi->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label custom-txt">WA Contact</label>
                        <p>Link Wa Group {{ $destinasi->link_wa_group}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(event) {
            event.preventDefault(); // Mencegah link langsung dijalankan
            
            if (confirm('Apakah Anda yakin ingin menghapus destinasi ini? Data yang dihapus tidak dapat dikembalikan.')) {
                // Jika user menekan OK, jalankan link
                window.location.href = event.target.href;
            }
            // Jika user menekan Cancel, tidak melakukan apa-apa
            return false;
        }
    </script>
@endsection
