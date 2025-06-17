@extends('layouts.app')
@section('title', 'Order List Page')
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

        .table thead th {
            background-color: #F2F5F7;
            color: black;
            font-weight: normal;
            border: none;
            padding: 16px;
                      
        }
        .table tbody td {
            padding: 16px;
            border-bottom: 1px solid #DEE2E6;
            color: #212529;
                  
        }
        .badge-success {
            background-color: #D4EDDB;
            color: #62BC72;
            font-weight: normal;
            padding: 6px 12px;
            border-radius: 999px;
        }
        .badge-pending {
            background-color: #EDE9D4;
            color: #BCB062;
            font-weight: normal;
            padding: 6px 12px;
            border-radius: 999px;
        }
        .badge-danger {
            background-color: #F8D7DA;
            color: #DC3545;
            font-weight: normal;
            padding: 6px 12px;
            border-radius: 999px;
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
                <a href="{{ route('driver.destination-list') }}" class="mt-5"><img
                        src="{{ asset('icons/icon-destination.svg') }}" alt="Icon Destination"> Destination</a>
                <a href="{{ route('driver.order-list') }}" class="active"><img
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
                    <a href="{{ route('driver.destination-list') }}" class="mt-5"><img
                        src="{{ asset('icons/icon-destination.svg') }}" alt="Icon Destination"> Destination</a>
                    <a href="{{ route('driver.order-list') }}" class="active"><img
                        src="{{ asset('icons/icon-order.svg') }}" alt="Icon Order"> Order</a>
                </div>
                <div class="mt-auto mb-4">
                    <a href="{{ route('logout') }}"><img src="{{ asset('icons/icon-logout.svg') }}" alt="Icon Logout"> Logout</a>
                </div>
            </div>
        </div>

        <!-- Main Content -->

        <div class="main-content container">
            <h1>Order List</h1>
            <p class="custom-txt">Manage your travel orders easily.</p>
            <div class="row mb-4">
                <form action="{{ route('driver.search-order') }}" method="GET" class="row">
                    <div class="col-md-4 col-8 mt-3">
                        <div class="input-group">
                            <span class="input-group-text custom-input">
                                <img src="{{ asset('icons/icon-search.svg') }}" alt="icon search">
                            </span>
                            <input type="text" class="form-control custom-input" placeholder="Customer Name"
                                name="search">
                        </div>
                    </div>
                    <div class="col-md-2 col-4 mt-3">
                        <button type="submit" class="custom-btn btn fw-bold">Search</button>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;">No</th>
                            <th>Customer Name</th>
                            <th>Destination</th>
                            <th>Price</th>
                            <th style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;">Payment Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $key => $order)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $order->user->nama }}</td>
                            <td>{{ $order->destinasi->check_point }} - {{ $order->destinasi->end_point }}</td>
                            <td>Rp{{ number_format($order->harga_kursi, 0, ',', '.') }}</td>
                            <td>
                                @if($order->status == 'finished')
                                    <span class="badge-success">Completed</span>
                                @elseif($order->status == 'pending')
                                    <span class="badge-pending">Pending</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="badge-danger">Cancelled</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No orders found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
