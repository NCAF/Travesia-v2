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
            <div class="row mb-4">
                <div class="col-md-4 col-8 mt-3">
                    <div class="input-group">
                        <span class="input-group-text custom-input">
                            <img src="{{ asset('icons/icon-search.svg') }}" alt="icon search">
                        </span>
                        <input type="text" class="form-control custom-input" placeholder="Location Name">
                    </div>
                </div>
                <div class="col-md-2 col-4 mt-3">
                    <button class="custom-btn btn fw-bold">Search</button>
                </div>
                <div class="col-md-6 text-end mt-3">
                    <button class="custom-btn-outline btn">Add Destination</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-12 mb-2">
                    <div class="custom-card p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4>Madenpa 80</h4>
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
                            <div class="col-md-4 text-end">
                                <button class="status-btn">Completed</button>
                                <h4>IDR 300.000 <span class="custom-txt fs-6">/Kursi</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
