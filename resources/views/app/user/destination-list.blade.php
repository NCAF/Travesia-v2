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
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .custom-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            color: inherit;
        }

        .status-btn {
            background-color: #D4EDDB;
            color: #62BC72;
            border-radius: 999px;
            border: 1px solid #D4EDDB;
            text-decoration: none;
            padding: 8px 16px;
        }

        /* Form label styling */
        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            color: #212529;
            font-weight: 500;
        }

        .form-label img {
            width: 20px;
            height: 20px;
        }

        /* Select2 styling */
        .select2-container {
            width: 100% !important;
        }

        .select2-container .select2-selection--single {
            height: 45px !important;
            border: none !important;
            border-radius: 8px !important;
            background-color: #F8F9FA !important;
            padding: 8px 12px !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #6C757D !important;
            line-height: 28px !important;
            padding-left: 12px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #6C757D !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 45px !important;
        }

        .select2-dropdown {
            border: none !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        }

        
    </style>
@endpush

@section('content')
    <div class="position-relative">
        <img src="{{ asset('img/destination-list.png') }}" alt="Destination List" class="img-fluid w-100">
        <div class="search-container bg-white shadow-lg">
            <form action="{{ route('search-destination') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-3 col-12">
                    <label class="form-label">
                        <img src="{{ asset('icons/icon-location.svg') }}" alt="Icon Location"> Origin
                    </label>
                    <select class="form-select select2" name="origin">
                        <option value="">Select city</option>
                        @foreach($uniqueLocations ?? [] as $location)
                            <option value="{{ $location }}" {{ request('origin') == $location ? 'selected' : '' }}>{{ $location }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-12">
                    <label class="form-label">
                        <img src="{{ asset('icons/icon-destination.svg') }}" alt="Icon Destination"> Destination
                    </label>
                    <select class="form-select select2" name="destination">
                        <option value="">Select destination</option>
                        @foreach($uniqueLocations ?? [] as $location)
                            <option value="{{ $location }}" {{ request('destination') == $location ? 'selected' : '' }}>{{ $location }}</option>
                        @endforeach
                    </select>
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
            @if (!empty($destinasi) && count($destinasi) > 0)
                @foreach ($destinasi as $item)
                    <div class="col-md-12 col-12 mb-2 mt-3">
                        <a href="{{ route('user.detail-destination', $item->id) }}" class="custom-card p-4">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h4>{{ $item->travel_name }}</h4>
                                    <div class="row">
                                        <div class="col-md">
                                            <p>{{ $item->check_point }}</p>
                                            <p class="text-muted">
                                                {{ \Carbon\Carbon::parse($item->start_date)->format('H.i') }}</p>
                                        </div>
                                        <div class="col-md align-content-center">
                                            <img src="{{ asset('icons/icon-line-left.svg') }}" alt="line left">
                                        </div>
                                        <div class="col-md align-content-center">
                                            <p class="custom-txt">
                                                @php
                                                    $start = \Carbon\Carbon::parse($item->start_date);
                                                    $end = \Carbon\Carbon::parse($item->end_date);
                                                    $diffInMinutes = $start->diffInMinutes($end);
                                                    $hours = floor($diffInMinutes / 60);
                                                    $minutes = $diffInMinutes % 60;
                                                @endphp
                                                {{ $hours > 0 ? $hours . ' jam ' : '' }}{{ $minutes > 0 ? $minutes . ' menit' : '0 menit' }}
                                            </p>
                                        </div>
                                        <div class="col-md align-content-center">
                                            <img src="{{ asset('icons/icon-line-right.svg') }}" alt="line right">
                                        </div>
                                        <div class="col-md">
                                            <p>{{ $item->end_point }}</p>
                                            <p class="text-muted">
                                                {{ \Carbon\Carbon::parse($item->end_date)->format('H.i') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-end">
                                    <h4 class="mt-3">IDR {{ number_format($item->price, 0, ',', '.') }} <span
                                            class="custom-txt fs-6">/{{ $item->number_of_seats }}</span></h4>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            @else
                <div class="col-md-12 col-12 mb-2">
                    <div class="custom-card p-4">
                        <h4>No data found</h4>
                    </div>
                </div>
            @endif
        </div>
        @include('partials.footer')
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            minimumResultsForSearch: 5,
            allowClear: true,
            placeholder: 'Select city'
        });
    });
</script>
@endpush
