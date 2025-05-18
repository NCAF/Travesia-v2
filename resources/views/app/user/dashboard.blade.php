@extends('layouts.main')
@section('title', 'Dashboard User Page')
@push('styles')
    <style>
        .search-container {
            position: absolute;
            bottom: 5%;
            left: 50%;
            transform: translateX(-50%);
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
    </style>
@endpush
@section('content')
    <div class="container mt-2 position-relative">
        <div class="position-relative">
            <img src="{{ asset('img/hero-section.png') }}" alt="hero section" class="img-fluid w-100 rounded">
            <div class="search-container bg-white shadow-lg">
                <form action="{{ route('user.search-destination') }}" method="GET" class="row g-3 align-items-center">
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


        <div class="row mt-3">
            <div class="col-md-4">
                <p class="fs-1 fw-bold">1,590+</p>
                <p class="custom-txt">Trusted by thousands of travelers for unforgettable journeys.</p>
            </div>
            <div class="col-md-4">
                <p class="fs-1 fw-bold">100+</p>
                <p class="custom-txt">Explore over 100 amazing destinations around the world.</p>
            </div>
            <div class="col-md-4">
                <p class="fs-1 fw-bold">523+</p>
                <p class="custom-txt">Thousands of precious moments have been created with us. </p>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-10">
                <h1>Top Destination</h1>
            </div>
            <div class="col-auto mt-3">
                <button class="btn custom-btn-outline rounded-circle" style="background-color: white;">
                    <img src="{{ asset('icons/icon-arrow-left.svg') }}" alt="arrow left" class="img-fluid">
                </button>
            </div>
            <div class="col-auto mt-3">
                <button class="btn custom-btn rounded-circle">
                    <img src="{{ asset('icons/icon-arrow-right.svg') }}" alt="arrow right" class="img-fluid">
                </button>
            </div>
        </div>
        <img src="{{ asset('img/top-destination.png') }}" alt="Top Destination" class="img-fluid mt-3">
        <div class="row mt-5">
            <div class="col-md">
                <h1>Our Service</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-12 mb-2">
                <div class="custom-card p-4">
                    <img src="{{ asset('icons/icon-ticket-booking.svg') }}" alt="icon ticket booking">
                    <h5 class="fw-normal mt-2">Ticket Booking</h5>
                    <p class="custom-txt mt-2 pb-4">Easily book your desired trip with just a few clicks.</p>
                </div>
            </div>
            <div class="col-md-3 col-12 mb-2">
                <div class="custom-card p-4">
                    <img src="{{ asset('icons/icon-messaging.svg') }}" alt="icon messaging">
                    <h5 class="fw-normal mt-2">Messaging</h5>
                    <p class="custom-txt mt-2">Stay connected with agents and fellow travelers in real-time.</p>
                </div>
            </div>
            <div class="col-md-3 col-12 mb-2">
                <div class="custom-card p-4">
                    <img src="{{ asset('icons/icon-searching.svg') }}" alt="icon searching">
                    <h5 class="fw-normal mt-2">Searching</h5>
                    <p class="custom-txt mt-2">Find your ideal destination quickly with our smart search feature.</p>
                </div>
            </div>
            <div class="col-md-3 col-12">
                <div class="custom-card p-4">
                    <img src="{{ asset('icons/icon-payment.svg') }}" alt="icon payment">
                    <h5 class="fw-normal mt-2">Payment</h5>
                    <p class="custom-txt mt-2 pb-4">Secure and hassle-free transactions.</p>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md">
                <h1>Behind Every Journey</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-12 my-2">
                <div class="custom-card p-4">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="{{ asset('icons/icon-img.svg') }}" alt="icon img" height="40" width="40">
                        </div>
                        <div class="col-md-10">
                            <p>Muhammad Adib Firmansyah
                                <span class="custom-txt">Frontend Developer</span>
                            </p>
                        </div>
                    </div>
                    <p class="custom-txt mt-2">Terlalu capee... untuk did denganarrr</p>
                </div>
            </div>
            <div class="col-md-4 col-12 my-2">
                <div class="custom-card p-4">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="{{ asset('icons/icon-img.svg') }}" alt="icon img" height="40" width="40">
                        </div>
                        <div class="col-md-10">
                            <p>Muhammad Adib Firmansyah
                                <span class="custom-txt">Frontend Developer</span>
                            </p>
                        </div>
                    </div>
                    <p class="custom-txt mt-2">Terlalu capee... untuk did denganarrr</p>
                </div>
            </div>
            <div class="col-md-4 col-12 my-2">
                <div class="custom-card p-4">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="{{ asset('icons/icon-img.svg') }}" alt="icon img" height="40" width="40">
                        </div>
                        <div class="col-md-10">
                            <p>Muhammad Adib Firmansyah
                                <span class="custom-txt">Frontend Developer</span>
                            </p>
                        </div>
                    </div>
                    <p class="custom-txt mt-2">Terlalu capee... untuk did denganarrr</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-12 my-2">
                <div class="custom-card p-4">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="{{ asset('icons/icon-img.svg') }}" alt="icon img" height="40" width="40">
                        </div>
                        <div class="col-md-10">
                            <p>Muhammad Adib Firmansyah
                                <span class="custom-txt">Frontend Developer</span>
                            </p>
                        </div>
                    </div>
                    <p class="custom-txt mt-2">Terlalu capee... untuk did denganarrr</p>
                </div>
            </div>
            <div class="col-md-4 col-12 my-2">
                <div class="custom-card p-4">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="{{ asset('icons/icon-img.svg') }}" alt="icon img" height="40" width="40">
                        </div>
                        <div class="col-md-10">
                            <p>Muhammad Adib Firmansyah
                                <span class="custom-txt">Frontend Developer</span>
                            </p>
                        </div>
                    </div>
                    <p class="custom-txt mt-2">Terlalu capee... untuk did denganarrr</p>
                </div>
            </div>
            <div class="col-md-4 col-12 my-2">
                <div class="custom-card p-4">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="{{ asset('icons/icon-img.svg') }}" alt="icon img" height="40" width="40">
                        </div>
                        <div class="col-md-10">
                            <p>Muhammad Adib Firmansyah
                                <span class="custom-txt">Frontend Developer</span>
                            </p>
                        </div>
                    </div>
                    <p class="custom-txt mt-2">Terlalu capee... untuk did denganarrr</p>
                </div>
            </div>
        </div>
        <img src="{{ asset('img/hero-section-2.png') }}" alt="hero section 2" class="img-fluid my-5">
        @include('partials.footer')
    </div>
@endsection
