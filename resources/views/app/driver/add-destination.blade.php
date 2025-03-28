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
                <a href="#" class="mt-5 active"><img src="{{ asset('icons/icon-destination.svg') }}"
                        alt="Icon Destination"> Destination</a>
                <a href="#"><img src="{{ asset('icons/icon-order.svg') }}" alt="Icon Order"> Order</a>
                <a href="#"><img src="{{ asset('icons/icon-chat.svg') }}" alt="Icon Chat"> Chat</a>
            </div>
        </div>

        <!-- Sidebar untuk Desktop -->
        <div class="sidebar d-none d-md-block">
            <img src="{{ asset('img/travesia.png') }}" alt="Logo Travesia" width="156" height="33">
            <a href="#" class="mt-5 active"><img src="{{ asset('icons/icon-destination.svg') }}"
                    alt="Icon Destination"> Destination</a>
            <a href="#"><img src="{{ asset('icons/icon-order.svg') }}" alt="Icon Order"> Order</a>
            <a href="#"><img src="{{ asset('icons/icon-chat.svg') }}" alt="Icon Chat"> Chat</a>
        </div>

        <!-- Main Content -->
        <div class="main-content container">
            <h1>Destination</h1>
            <p class="custom-txt">Manage your travel destinations easily.</p>
            <div class="row mb-4">
                <div class="col-md-6 mt-3">
                    <h4>Add Destination</h4>
                </div>
                <div class="col-md-6 text-end mt-3">
                    <button class="custom-btn btn fw-bold">Add Destination</button>
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
                                <input type="text" id="travel_name" class="custom-input form-control"
                                    placeholder="Travel Company travel_name" name="travel_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" id="start_date" class="custom-input form-control"
                                    placeholder="Travel Company Name" name="start_date">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="custom-input form-control" name="description" id="description" cols="30" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="check_point" class="form-label">Check Point</label>
                                <input type="text" id="check_point" class="custom-input form-control"
                                    placeholder="Travel Company Name" name="check_point">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_point" class="form-label">End Point</label>
                                <input type="text" id="end_point" class="custom-input form-control"
                                    placeholder="Travel Company Name" name="end_point">
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
                                <input type="text" id="venicle_type" class="custom-input form-control"
                                    placeholder="Nissa" name="venicle_type">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="plate_number" class="form-label">Plate Number</label>
                                <input type="text" id="plate_number" class="custom-input form-control"
                                    placeholder="G4NTENG" name="plate_number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="seats" class="form-label">Number of Seats</label>
                                <input type="text" id="seats" class="custom-input form-control" placeholder="80"
                                    name="seats">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="image_destination" class="form-label">Upload Image</label>
                        <div class="drag-drop dropzone">
                            <input type="file" id="image_destination" class="form-control">
                            <img src="{{ asset('icons/icon-cloud.svg') }}" alt="Upload Icon">
                            <p class="custom-txt">Click to Upload or Drag and Drop</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="text" id="price" class="custom-input form-control" placeholder="IDR"
                            name="price">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let dropzones = document.querySelectorAll(".dropzone");

            dropzones.forEach((dropzone) => {
                let fileInput = dropzone.querySelector("input[type='file']");
                let isProcessing = false; // Mencegah trigger dua kali

                // Klik area dropzone untuk membuka file picker
                dropzone.addEventListener("click", function() {
                    fileInput.click();
                });

                // Saat file dipilih
                fileInput.addEventListener("change", function() {
                    if (fileInput.files.length > 0 && !isProcessing) {
                        isProcessing = true;
                        processFile(fileInput.files[0], dropzone, () => {
                            isProcessing = false;
                        });
                    }
                });

                // Drag & Drop Handling
                dropzone.addEventListener("dragover", function(e) {
                    e.preventDefault();
                    dropzone.style.borderColor = "#3C8EE1";
                });

                dropzone.addEventListener("dragleave", function() {
                    dropzone.style.borderColor = "#ccc";
                });

                dropzone.addEventListener("drop", function(e) {
                    e.preventDefault();
                    let file = e.dataTransfer.files[0];
                    fileInput.files = e.dataTransfer.files;
                    if (!isProcessing) {
                        isProcessing = true;
                        processFile(file, dropzone, () => {
                            isProcessing = false;
                        });
                    }
                });

                // Fungsi untuk memproses dan menampilkan gambar
                function processFile(file, dropzoneElement, callback) {
                    let reader = new FileReader();
                    let img = new Image();

                    reader.onload = function(e) {
                        img.src = e.target.result;

                        img.onload = function() {
                            let canvas = document.createElement("canvas");
                            let ctx = canvas.getContext("2d");

                            canvas.width = 300;
                            canvas.height = 200;

                            let scale = Math.min(canvas.width / img.width, canvas.height / img
                                .height);
                            let newWidth = img.width * scale;
                            let newHeight = img.height * scale;

                            let xOffset = (canvas.width - newWidth) / 2;
                            let yOffset = (canvas.height - newHeight) / 2;

                            ctx.drawImage(img, xOffset, yOffset, newWidth, newHeight);

                            dropzoneElement.innerHTML = `<img src="${canvas.toDataURL("image/png")}" 
                        style="width:100%; height:auto; border-radius:10px;">`;

                            fileInput.value = ""; // Reset input file setelah upload
                            if (callback) callback();
                        };
                    };

                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endpush
