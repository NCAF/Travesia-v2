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

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3C8EE1 !important;
            color: white !important;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #3C8EE1 !important;
            color: white !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #3C8EE1 !important;
            line-height: 28px !important;
            padding-left: 12px !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #3C8EE1 !important;
        }

        /* Custom Select2 Styles */
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #3C8EE1 !important;
            color: #212529 !important;
        }

        .select2-container--default .select2-selection--single {
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


            <form action="{{ route('driver.add-destination.post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
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
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="travel_name" class="form-label">Travel Name</label>
                                    <input type="text" id="travel_name"
                                        class="custom-input form-control @error('travel_name') is-invalid @enderror"
                                        placeholder="Travel Company Name" name="travel_name"
                                        value="{{ old('travel_name') }}">
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
                                        placeholder="Travel Company Name" name="start_date"
                                        value="{{ old('start_date') }}">
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
                                        placeholder="Travel Company Name" name="end_date" value="{{ old('end_date') }}">
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
                                            <option value="{{ $point }}" {{ old('check_point') == $point ? 'selected' : '' }}>{{ $point }}</option>
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
                                            <option value="{{ $point }}" {{ old('end_point') == $point ? 'selected' : '' }}>{{ $point }}</option>
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
                                    id="description" cols="30" rows="5">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex mt-5">
                            <p>Venicle Information</p>
                            <hr class="flex-grow-1 ms-2">
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="venicle_type" class="form-label">Venicle Type</label>
                                    <input type="text" id="venicle_type"
                                        class="custom-input form-control @error('vehicle_type') is-invalid @enderror"
                                        placeholder="Nissa" name="vehicle_type" value="{{ old('vehicle_type') }}">
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
                                        placeholder="G4NTENG" name="plate_number" value="{{ old('plate_number') }}">
                                    @error('plate_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="seats" class="form-label">Number of Seats</label>
                                    <input type="text" id="seats"
                                        class="custom-input form-control @error('number_of_seats') is-invalid @enderror"
                                        placeholder="80" name="number_of_seats" value="{{ old('number_of_seats') }}">
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
                                <input type="file" id="foto"
                                    class="form-control @error('foto') is-invalid @enderror" name="foto"
                                    accept="image/*">
                                <img src="{{ asset('icons/icon-cloud.svg') }}" alt="Upload Icon">
                                <p class="custom-txt">Click to Upload or Drag and Drop</p>
                                @error('foto')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" id="price"
                                class="custom-input form-control @error('price') is-invalid @enderror" placeholder="IDR"
                                name="price" value="{{ old('price') }}">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="link_wa_group" class="form-label">Link Wa Group</label>
                            <input type="text" id="link_wa_group"
                                class="custom-input form-control @error('link_wa_group') is-invalid @enderror" placeholder="Link Wa Group"
                                name="link_wa_group" value="{{ old('link_wa_group') }}">
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
                minimumResultsForSearch: 0,
                selectionCssClass: 'select2-selection--custom'
            });

            // Initialize Destination (end_point) select2
            $('#end_point').select2({
                width: '100%',
                allowClear: true,
                placeholder: 'Select end point',
                minimumResultsForSearch: 0,
                selectionCssClass: 'select2-selection--custom'
            });

            let dropzones = document.querySelectorAll(".dropzone");

            dropzones.forEach((dropzone) => {
                const originalInput = dropzone.querySelector("input[type='file']");
                const defaultContent = `
                    <input type="file" id="${originalInput.id}" class="${originalInput.className}" name="${originalInput.name}" accept="${originalInput.accept}">
                    <img src="{{ asset('icons/icon-cloud.svg') }}" alt="Upload Icon">
                    <p class="custom-txt">Click to Upload or Drag and Drop</p>
                `;

                function setupDropzone() {
                    let fileInput = dropzone.querySelector("input[type='file']");

                    // Klik area dropzone untuk membuka file picker
                    dropzone.onclick = function(e) {
                        if (e.target !== fileInput && e.target.tagName !== 'BUTTON') {
                            fileInput.click();
                        }
                    };

                    // Saat file dipilih
                    fileInput.onchange = function() {
                        if (this.files.length > 0) {
                            processFile(this.files[0]);
                        }
                    };

                    // Drag & Drop Handling
                    dropzone.ondragover = function(e) {
                        e.preventDefault();
                        this.style.borderColor = "#3C8EE1";
                    };

                    dropzone.ondragleave = function() {
                        this.style.borderColor = "#ccc";
                    };

                    dropzone.ondrop = function(e) {
                        e.preventDefault();
                        this.style.borderColor = "#ccc";

                        if (e.dataTransfer.files.length > 0) {
                            const newFile = e.dataTransfer.files[0];
                            // Create a new DataTransfer object
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(newFile);
                            // Set the files property of the file input
                            fileInput.files = dataTransfer.files;
                            processFile(newFile);
                        }
                    };
                }

                // Fungsi untuk memproses dan menampilkan gambar
                function processFile(file) {
                    if (!file.type.startsWith('image/')) {
                        alert('Please upload an image file');
                        resetDropzone();
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = new Image();
                        img.src = e.target.result;

                        img.onload = function() {
                            // Simpan file yang dipilih
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);

                            dropzone.innerHTML = `
                                <input type="file" id="${originalInput.id}" class="${originalInput.className}" name="${originalInput.name}" accept="${originalInput.accept}">
                                <img src="${e.target.result}" style="width:100%; height:200px; object-fit:cover; border-radius:10px;">
                                <button type="button" class="btn btn-link text-danger mt-2" onclick="resetDropzone(event)">Remove Image</button>
                            `;

                            // Set file ke input yang baru
                            const newFileInput = dropzone.querySelector('input[type="file"]');
                            newFileInput.files = dataTransfer.files;
                            setupDropzone();
                        };
                    };
                    reader.readAsDataURL(file);
                }

                // Fungsi untuk reset dropzone
                window.resetDropzone = function(event) {
                    if (event) {
                        event.stopPropagation();
                    }
                    dropzone.innerHTML = defaultContent;
                    setupDropzone();
                };

                // Setup awal
                setupDropzone();
            });
        });
    </script>
@endpush
