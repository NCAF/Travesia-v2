@extends('layouts.app')
@section('title', 'Add Destination Page')

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
            border: 2px dashed #ccc;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 250px;
            cursor: pointer;
            position: relative;
            background-color: #F2F5F7;
        }

        .drag-drop img {
            width: 50px;
            margin-bottom: 10px;
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
                <a href="{{ route('driver.destination-list') }}" class="mt-5 active"><img src="{{ asset('icons/icon-destination.svg') }}"
                        alt="Icon Destination"> Destination</a>
                <a href="#"><img src="{{ asset('icons/icon-order.svg') }}" alt="Icon Order"> Order</a>
                <a href="#"><img src="{{ asset('icons/icon-chat.svg') }}" alt="Icon Chat"> Chat</a>
                <a href="{{ route('logout') }}"><img src="{{ asset('icons/icon-logout.svg') }}" alt="Icon Logout"> Logout</a>
            </div>
        </div>

        <!-- Sidebar untuk Desktop -->
        <div class="sidebar d-none d-md-block">
            <img src="{{ asset('img/travesia.png') }}" alt="Logo Travesia" width="156" height="33">
            <a href="{{ route('driver.destination-list') }}" class="mt-5 active"><img src="{{ asset('icons/icon-destination.svg') }}"
                    alt="Icon Destination"> Destination</a>
            <a href="#"><img src="{{ asset('icons/icon-order.svg') }}" alt="Icon Order"> Order</a>
            <a href="#"><img src="{{ asset('icons/icon-chat.svg') }}" alt="Icon Chat"> Chat</a>
            <a href="{{ route('logout') }}"><img src="{{ asset('icons/icon-logout.svg') }}" alt="Icon Logout"> Logout</a>
        </div>

        <!-- Main Content -->
         <form action="{{ route('driver.add-destination.post') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
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
                
                <!-- Error Message -->
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                <!-- Validation Errors -->
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Please fix the following errors:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                <div class="row mb-4">
                <div class="col-md-6 mt-3">
                    <h4>Add Destination</h4>
                </div>
                <div class="col-md-6 text-end mt-3">
                    <button type="submit" class="custom-btn btn fw-bold">Add Destination</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="d-flex">
                        <p>General Information</p>
                        <hr class="flex-grow-1 ms-2">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="travel_name" class="form-label">Travel Name</label>
                                <input type="text" id="travel_name" class="custom-input form-control @error('travel_name') is-invalid @enderror"
                                    placeholder="Travel Company" name="travel_name" value="{{ old('travel_name') }}">
                                @error('travel_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" id="start_date" class="custom-input form-control @error('start_date') is-invalid @enderror"
                                    placeholder="Travel Company Name" name="start_date" value="{{ old('start_date') }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="custom-input form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" id="description" cols="30" rows="5">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="check_point" class="form-label">Check Point</label>
                                <input type="text" id="check_point" class="custom-input form-control @error('check_point') is-invalid @enderror"
                                    placeholder="Travel Company Name" name="check_point" value="{{ old('check_point') }}">
                                @error('check_point')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_point" class="form-label">End Point</label>
                                <input type="text" id="end_point" class="custom-input form-control @error('end_point') is-invalid @enderror"
                                    placeholder="Travel Company Name" name="end_point" value="{{ old('end_point') }}">
                                @error('end_point')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <p>Venicle Information</p>
                        <hr class="flex-grow-1 ms-2">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="venicle_type" class="form-label">Venicle Type</label>
                                <input type="text" id="venicle_type" class="custom-input form-control @error('vehicle_type') is-invalid @enderror"
                                    placeholder="Nissa" name="vehicle_type" value="{{ old('vehicle_type') }}">
                                @error('vehicle_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="plate_number" class="form-label">Plate Number</label>
                                <input type="text" id="plate_number" class="custom-input form-control @error('plate_number') is-invalid @enderror"
                                    placeholder="G4NTENG" name="plate_number" value="{{ old('plate_number') }}">
                                @error('plate_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="seats" class="form-label">Number of Seats</label>
                                <input type="text" id="seats" class="custom-input form-control @error('number_of_seats') is-invalid @enderror" placeholder="80"
                                    name="number_of_seats" value="{{ old('number_of_seats') }}">
                                @error('number_of_seats')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="foto" class="form-label">Upload Image</label>
                        <div class="drag-drop dropzone @error('foto') border-danger @enderror">
                            <input type="file" id="foto" class="form-control @error('foto') is-invalid @enderror" name="foto" accept="image/*">
                            <img src="{{ asset('icons/icon-cloud.svg') }}" alt="Upload Icon">
                            <p class="custom-txt">Click to Upload or Drag and Drop</p>
                            @error('foto')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="text" id="price" class="custom-input form-control @error('price') is-invalid @enderror" placeholder="IDR"
                            name="price" value="{{ old('price') }}">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

