@extends('layouts.main')
@section('title', 'Detail Destination Page')
@section('content')
    <div class="container">
        <img src="{{ asset('images/' . $destinasi->foto) }}" alt="detail destination" class="img-fluid">
        <div class="row mt-4">
            <div class="col-md-6">
                <h1>{{ $destinasi->travel_name }}</h1>
                <p class="custom-txt">{{ $destinasi->start_date }}</p>
            </div>
            <div class="col-md-6 text-end">
                <h4>IDR {{ number_format($destinasi->price, 0, ',', '.') }} <span class="custom-txt fs-6">/{{ $destinasi->number_of_seats }}</span></h4>
                <a href="{{ route('user.passenger-details', $destinasi->id) }}" class="custom-btn btn fw-bold">Booking Now</a>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('icons/icon-description.svg') }}" alt="icon description" class="icon">
                    <h4 class="ms-2">Description</h4>
                    <hr class="flex-grow-1 ms-2">
                </div>
                <p class="custom-txt mt-3">{{ $destinasi->deskripsi }}</p>
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
                        <p>{{ $destinasi->vehicle_type }}</p>
                        <p class="custom-txt">Number of Seats</p>
                        <p>{{ $destinasi->number_of_seats }}</p>
                    </div>
                    <div class="col-md">
                        <p class="custom-txt">Plate Number</p>
                        <p>{{ $destinasi->plate_number }}</p>
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
                        <p>{{ $destinasi->check_point }}</p>
                        <p class="custom-txt">{{ $destinasi->start_date }}</p>
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
                        <p>{{ $destinasi->end_point }}</p>
                        <p class="custom-txt">{{ $destinasi->end_date }}</p>
                    </div>
                </div>
            </div>
        </div>
        @include('partials.footer')
    </div>
@endsection
