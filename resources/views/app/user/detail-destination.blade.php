@extends('layouts.main')
@section('title', 'Detail Destination Page')
@section('content')
    <div class="container">
        <img src="{{ asset('img/detail-destination.png') }}" alt="detail destination" class="img-fluid">
        <div class="row mt-4">
            <div class="col-md-6">
                <h1>Madenpa 80</h1>
                <p class="custom-txt">Sunday, 17 Maret 2025</p>
            </div>
            <div class="col-md-6 text-end">
                <h4>IDR 300.000 <span class="custom-txt fs-6">/kursi</span></h4>
                <button class="custom-btn btn fw-bold">Booking Now</button>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('icons/icon-description.svg') }}" alt="icon description" class="icon">
                    <h4 class="ms-2">Description</h4>
                    <hr class="flex-grow-1 ms-2">

                </div>
                <p class="custom-txt mt-3">Lorem ipsum dolor sit amet consectetur. Nisi neque fringilla sollicitudin
                    maecenas. Vulputate sit id et maecenas nulla pulvinar feugiat. Fermentum commodo ac amet lacus
                    feugiat risus in. Neque nisl auctor justo mauris.</p>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('icons/icon-specification.svg') }}" alt="icon specification" class="icon">
                    <h4 class="ms-2">Specification</h4>
                    <hr class="flex-grow-1 ms-2">
                </div>
                <div class="row mt-3">
                    <div class="col-md">
                        <p class="custom-txt">Venicle Type</p>
                        <p>Avanza Putih</p>
                        <p class="custom-txt">Number of Seats</p>
                        <p>8 Seats</p>
                    </div>
                    <div class="col-md">
                        <p class="custom-txt">Plate Number</p>
                        <p>12 NSI4 23</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('icons/icon-destination.svg') }}" alt="icon destination">
                    <h4 class="ms-2">Route</h4>
                    <hr class="flex-grow-1 ms-2">
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <p>Malang</p>
                        <p class="custom-txt">12.30 WIB</p>
                    </div>
                    <div class="col-md-2 align-content-center">
                        <img src="{{ asset('icons/icon-line-left.svg') }}" alt="line left">
                    </div>
                    <div class="col-md-2 align-content-center">
                        <p class="custom-txt"> 11j 32m </p>
                    </div>
                    <div class="col-md-2 align-content-center">
                        <img src="{{ asset('icons/icon-line-right.svg') }}" alt="line right">
                    </div>
                    <div class="col-md-2">
                        <p>Denpasar</p>
                        <p class="custom-txt">01.30 WIB</p>
                    </div>
                </div>
            </div>
        </div>
        @include('partials.footer')
    </div>
@endsection
