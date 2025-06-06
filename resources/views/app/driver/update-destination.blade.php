@extends('layouts.app')
@section('title', 'Edit Destination Page')

@php
    use Illuminate\Support\Str;
@endphp

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

        /* Custom Select2 Styles */
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
            color: #212529 !important;
            line-height: 28px !important;
            padding-left: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #6C757D !important;
        }

        /* Hide default Select2 arrow */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none !important;
        }

        /* Dropdown styling */
        .select2-dropdown {
            border: none !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
            margin-top: 4px !important;
            background-color: #F8F9FA !important;
        }

        .select2-search--dropdown {
            padding: 8px !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #E5E7EB !important;
            border-radius: 6px !important;
            padding: 8px !important;
        }

        .select2-results__option {
            padding: 8px 12px !important;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #3C8EE1 !important;
            color: #212529 !important;
        }

        .select2-results__option--highlighted[aria-selected=true] {
            background-color: #3C8EE1 !important;
            color: #212529 !important;
        }

        .select2-results__option--highlighted[aria-selected] {
            background-color: #3C8EE1 !important;
            color: #212529 !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3C8EE1 !important;
            color: #212529 !important;
        }

        .select2-container--default .select2-results__option {
            color: #212529 !important;
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
                <a href="{{ route('logout') }}"><img src="{{ asset('icons/icon-logout.svg') }}" alt="Icon Logout">
                    Logout</a>
            </div>
        </div>

        <!-- Sidebar untuk Desktop -->
        <div class="sidebar d-none d-md-block">
            <img src="{{ asset('img/travesia.png') }}" alt="Logo Travesia" width="156" height="33">
            <a href="{{ route('driver.destination-list') }}" class="mt-5 active"><img
                    src="{{ asset('icons/icon-destination.svg') }}" alt="Icon Destination"> Destination</a>
            <a href="{{ route('logout') }}"><img src="{{ asset('icons/icon-logout.svg') }}" alt="Icon Logout"> Logout</a>
        </div>

        <!-- Main Content -->

        <div class="main-content container">
            <h1>Destination</h1>
            <p class="custom-txt">Manage your travel destinations easily.</p>

            <!-- Success Message -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Error Message -->
            @if (session('error'))
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
            <form action="{{ route('driver.update-destination.post', ['id' => $destinasi->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="row mb-4">
                    <div class="col-md-8 mt-3">
                        <h4>Edit Destination</h4>
                    </div>
                    <div class="col-md-4 text-end mt-3">
                        <button type="submit" class="custom-btn btn fw-bold">Edit Destination</button>
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
                                    <label for="travel_name" class="form-label">Travel Name</label>
                                    <input type="text" id="travel_name"
                                        class="custom-input form-control @error('travel_name') is-invalid @enderror"
                                        placeholder="Travel Company" name="travel_name"
                                        value="{{ old('travel_name', $destinasi->travel_name) }}">
                                    @error('travel_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="datetime-local" id="start_date"
                                        class="custom-input form-control @error('start_date') is-invalid @enderror"
                                        name="start_date"
                                        value="{{ old('start_date', \Carbon\Carbon::parse($destinasi->start_date)->format('Y-m-d\TH:i')) }}">

                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="datetime-local" id="end_date"
                                        class="custom-input form-control @error('end_date') is-invalid @enderror"
                                        name="end_date"
                                        value="{{ old('end_date', \Carbon\Carbon::parse($destinasi->end_date)->format('Y-m-d\TH:i')) }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="check_point" class="form-label">Check Point</label>
                                    <select id="check_point" class="form-select select2 @error('check_point') is-invalid @enderror" name="check_point">
                                        <option></option>
                                        @foreach($checkPoints as $point)
                                            <option value="{{ $point }}" {{ old('check_point', $destinasi->check_point) == $point ? 'selected' : '' }}>{{ $point }}</option>
                                        @endforeach
                                    </select>
                                    @error('check_point')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_point" class="form-label">End Point</label>
                                    <select id="end_point" class="form-select select2 @error('end_point') is-invalid @enderror" name="end_point">
                                        <option></option>
                                        @foreach($endPoints as $point)
                                            <option value="{{ $point }}" {{ old('end_point', $destinasi->end_point) == $point ? 'selected' : '' }}>{{ $point }}</option>
                                        @endforeach
                                    </select>
                                    @error('end_point')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="custom-input form-control @error('deskripsi') is-invalid @enderror" name="deskripsi"
                                    id="description" cols="30" rows="5">{{ old('deskripsi', $destinasi->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex mt-5">
                            <p>Vehicle Information</p>
                            <hr class="flex-grow-1 ms-2">
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="vehicle_type" class="form-label">Vehicle Type</label>
                                    <input type="text" id="vehicle_type"
                                        class="custom-input form-control @error('vehicle_type') is-invalid @enderror"
                                        placeholder="Nissan" name="vehicle_type"
                                        value="{{ old('vehicle_type', $destinasi->vehicle_type) }}">
                                    @error('vehicle_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="plate_number" class="form-label">Plate Number</label>
                                    <input type="text" id="plate_number"
                                        class="custom-input form-control @error('plate_number') is-invalid @enderror"
                                        placeholder="G4NTENG" name="plate_number"
                                        value="{{ old('plate_number', $destinasi->plate_number) }}">
                                    @error('plate_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="number_of_seats" class="form-label">Number of Seats</label>
                                    <input type="number" id="number_of_seats"
                                        class="custom-input form-control @error('number_of_seats') is-invalid @enderror"
                                        placeholder="80" name="number_of_seats"
                                        value="{{ old('number_of_seats', $destinasi->number_of_seats) }}">
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
                            <div class="drag-drop dropzone">
                                <input type="file" id="foto" name="foto" class="form-control">
                                @if ($destinasi->foto)
                                    <img src="{{ asset('images/' . $destinasi->foto) }}" alt="Destination Image"
                                        height="200" width="300">
                                @else
                                    <img src="{{ asset('icons/icon-camera.svg') }}" alt="Upload Image">
                                    <p>Upload your image here</p>
                                @endif
                            </div>
                            @error('foto')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="custom-input form-control @error('price') is-invalid @enderror"
                                id="price" placeholder="IDR" name="price"
                                value="{{ old('price', $destinasi->price) }}">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="link_wa_group" class="form-label">Link Wa Group</label>
                            <input type="text" class="custom-input form-control @error('link_wa_group') is-invalid @enderror"
                                id="link_wa_group" placeholder="IDR" name="link_wa_group"
                                value="{{ old('link_wa_group', $destinasi->link_wa_group) }}">
                            @error('link_wa_group')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </form>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Origin (check_point) select2
            $('#check_point').select2({
                width: '100%',
                allowClear: true,
                placeholder: 'Select check point',
                minimumResultsForSearch: 0
            });

            // Initialize Destination (end_point) select2
            $('#end_point').select2({
                width: '100%',
                allowClear: true,
                placeholder: 'Select end point',
                minimumResultsForSearch: 0
            });

            document.addEventListener("DOMContentLoaded", function() {
                let dropzone = document.querySelector(".dropzone");
                let fileInput = dropzone.querySelector("input[type='file']");
                let previewImage = dropzone.querySelector("img");

                function createNewFileInput() {
                    let newFileInput = document.createElement("input");
                    newFileInput.type = "file";
                    newFileInput.name = "foto";
                    newFileInput.className = "form-control";
                    newFileInput.style.display = "none"; // Sembunyikan input baru
                    dropzone.appendChild(newFileInput);

                    newFileInput.addEventListener("change", function() {
                        if (newFileInput.files.length > 0) {
                            updateImagePreview(newFileInput.files[0]);
                        }
                    });

                    return newFileInput;
                }

                function updateImagePreview(file) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                    };
                    reader.readAsDataURL(file);

                    // Hapus file input lama dan buat yang baru
                    fileInput.remove();
                    fileInput = createNewFileInput();
                }

                // Ketika klik area drag & drop, buka file picker
                dropzone.addEventListener("click", function() {
                    fileInput.click();
                });

                // Saat file di-drag masuk ke area dropzone
                dropzone.addEventListener("dragover", function(e) {
                    e.preventDefault();
                });

                // Saat file dilepas ke dropzone
                dropzone.addEventListener("drop", function(e) {
                    e.preventDefault();
                    let file = e.dataTransfer.files[0]; // Ambil file pertama yang didrag
                    updateImagePreview(file); // Perbarui gambar
                });

                // Ganti file input pertama kali
                fileInput.remove();
                fileInput = createNewFileInput();
            });
        });
    </script>
@endpush
