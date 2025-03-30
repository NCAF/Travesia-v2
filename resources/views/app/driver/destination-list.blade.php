@extends('layouts.app')
@section('title', 'Destination List Page')

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
                <a href="#" class="mt-5 active"><img src="{{ asset('icons/icon-destination.svg') }}"
                        alt="Icon Destination"> Destination</a>
                <a href="#"><img src="{{ asset('icons/icon-order.svg') }}" alt="Icon Order"> Order</a>
                <a href="#"><img src="{{ asset('icons/icon-chat.svg') }}" alt="Icon Chat"> Chat</a>
                <a href="{{ route('logout') }}"><img src="{{ asset('icons/icon-logout.svg') }}" alt="Icon Logout"> Logout</a>
            </div>
        </div>

        <!-- Sidebar untuk Desktop -->
        <div class="sidebar d-none d-md-block">
            <img src="{{ asset('img/travesia.png') }}" alt="Logo Travesia" width="156" height="33">
            <a href="#" class="mt-5 active"><img src="{{ asset('icons/icon-destination.svg') }}"
                    alt="Icon Destination"> Destination</a>
            <a href="#"><img src="{{ asset('icons/icon-order.svg') }}" alt="Icon Order"> Order</a>
            <a href="#"><img src="{{ asset('icons/icon-chat.svg') }}" alt="Icon Chat"> Chat</a>
            <a href="{{ route('logout') }}"><img src="{{ asset('icons/icon-logout.svg') }}" alt="Icon Logout"> Logout</a>
        </div>

        <!-- Main Content -->
        <div class="main-content container">
            <h1>Destination</h1>
            <p class="custom-txt">Manage your travel destinations easily.</p>
            
            <!-- Success Message -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            <div class="row mb-4">
                <form action="{{ route('driver.search-destination') }}" method="GET" class="row">
                    <div class="col-md-4 col-8 mt-3">
                        <div class="input-group">
                            <span class="input-group-text custom-input">
                                <img src="{{ asset('icons/icon-search.svg') }}" alt="icon search">
                            </span>
                            <input type="text" class="form-control custom-input" placeholder="Location Name" name="search">
                        </div>
                    </div>
                    <div class="col-md-2 col-4 mt-3">
                        <button type="submit" class="custom-btn btn fw-bold">Search</button>
                    </div>
                    <div class="col-md-6 text-end mt-3">
                        <a href="{{ route('driver.add-destination') }}" class="custom-btn-outline btn">Add Destination</a>
                    </div>
                </form>
            </div>
            <div class="row">
                @if(count($destinasi) > 0)
                    @foreach($destinasi as $item)
                    <div class="col-md-12 col-12 mb-2">
                        <div class="custom-card p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h4>{{ $item->travel_name }}</h4>
                                    <div class="row">
                                        <div class="col-md">
                                            <p>{{ $item->check_point }}</p>
                                            <p class="custom-txt">Starting Point</p>
                                        </div>
                                        <div class="col-md align-content-center">
                                            <img src="{{ asset('icons/icon-line-left.svg') }}" alt="line left">
                                        </div>
                                        <div class="col-md align-content-center">
                                            <p class="custom-txt">{{ $item->vehicle_type }} - {{ $item->plate_number }}</p>
                                        </div>
                                        <div class="col-md align-content-center">
                                            <img src="{{ asset('icons/icon-line-right.svg') }}" alt="line right">
                                        </div>
                                        <div class="col-md">
                                            <p>{{ $item->end_point }}</p>
                                            <p class="custom-txt">Destination</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <button class="status-btn">Available</button>
                                    <h4>IDR <span class="custom-txt fs-6">{{ number_format($item->price, 0, ',', '.') }}</span></h4>
                                    <a href="{{ route('driver.detail-destination', ['id' => $item->id]) }}" class="btn custom-btn-outline mt-2">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="col-md-12 text-center">
                    <p>No destinations found. <a href="{{ route('driver.add-destination') }}">Add your first destination</a></p>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
