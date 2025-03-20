@extends('layouts.main')
@section('title', 'Destination List Page')
@push('styles')
    <style>
        .search-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.8);
            padding: 16px;
            border-radius: 24px;
            width: 90%;
            backdrop-filter: blur(10px);
        }


        /* Untuk Tablet (Lebar Maks 1024px) */
        @media (max-width: 1024px) {
            .search-container {
                position: static;
                width: 100%;
                transform: none;
                margin-top: 16px;
            }
        }

        /* Untuk Mobile (Lebar Maks 768px) */
        @media (max-width: 768px) {
            .search-container {
                position: static;
                width: 100%;
                transform: none;
                margin-top: 16px;
            }
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
    </style>
@endpush
@section('content')
    <div class="position-relative">
        <img src="{{ asset('img/destination-list.png') }}" alt="Destination List" class="img-fluid">
        <div class="search-container bg-white shadow-lg">
            <form action="#" method="GET" class="row g-3 align-items-center">
                <div class="col-md-3 col-12">
                    <label class="form-label">
                        <img src="{{ asset('icons/icon-location.svg') }}" alt="Icon Location"> Origin
                    </label>
                    <input type="text" class="form-control custom-input" name="origin" placeholder="Select city">
                </div>
                <div class="col-md-3 col-12">
                    <label class="form-label">
                        <img src="{{ asset('icons/icon-destination.svg') }}" alt="Icon Destination"> Destination
                    </label>
                    <input type="text" class="form-control custom-input" name="destination"
                        placeholder="Select destination">
                </div>
                <div class="col-md-3 col-12">
                    <label class="form-label">
                        <img src="{{ asset('icons/icon-date.svg') }}" alt="Icon Date"> Date
                    </label>
                    <input type="date" class="form-control custom-input" name="date">
                </div>
                <div class="col-md-3 col-12 mt-5">
                    <button type="submit" class="custom-btn btn w-100 fw-bold">Search</button>
                </div>
            </form>
        </div>
    </div>
    <div class="container">
        <h1 class="mt-5">Result</h1>
        <div class="row">
            <div class="col-md-12 col-12 mb-2">
                <div class="custom-card p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
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
                        <div class="col-md-6 text-end">
                            <button class="status-btn">Available</button>
                            <h4>IDR 300.000 <span class="custom-txt fs-6">/seat</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-12 mb-2">
                <div class="custom-card p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
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
                        <div class="col-md-6 text-end">
                            <button class="status-btn">Available</button>
                            <h4>IDR 300.000 <span class="custom-txt fs-6">/seat</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('partials.footer')
    </div>

@endsection
