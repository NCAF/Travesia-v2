@extends('layouts.app')
@section('title', 'Register Page')

@push('styles')
    <style>
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

        span {
            color: #3C8EE1;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row text-center mt-5">
            <div class="col-md">
                <img src="{{ asset('img/travesia.png') }}" alt="logo travesia" width="118" height="24">
            </div>
        </div>
        <div class="row text-center mt-3">
            <div class="col-md">
                <h1>Get Started</h1>
            </div>
        </div>
        <div class="row text-center mt-3">
            <div class="col-md">
                <p class="custom-txt fw-normal">Join us and explore the world.</p>
            </div>
        </div>
        <form>
            <div class="row justify-content-center">
                <!-- Kolom Input Form -->
                <div class="col-md-4 order-md-1 order-1">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" class="custom-input form-control" placeholder="your name">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" class="custom-input form-control"
                            placeholder="your_email@example.com">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" class="custom-input form-control" placeholder="********">
                    </div>
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label">Confirm Password</label>
                        <input type="password" id="confirm-password" class="custom-input form-control"
                            placeholder="********">
                    </div>

                    <!-- Input File (Licensi) Dipindah ke Sini di Mobile -->
                    <div class="mb-3 d-md-none">
                        <label for="license_mobile" class="form-label">Licensi</label>
                        <div class="drag-drop dropzone">
                            <input type="file" id="license_mobile" class="form-control d-none">
                            <img src="{{ asset('icons/icon-cloud.svg') }}" alt="Upload Icon">
                            <p class="custom-txt">Click to Upload or Drag and Drop</p>
                        </div>
                    </div>

                    <!-- Button Sign Up -->
                    <button type="submit" class="custom-btn btn w-100 fw-bold">Sign Up</button>
                </div>

                <!-- Kolom Input File untuk Desktop -->
                <div class="col-md-4 order-md-2 order-2 d-none d-md-block">
                    <div class="mb-3">
                        <label for="license_desktop" class="form-label">Licensi</label>
                        <div class="drag-drop dropzone">
                            <input type="file" id="license_desktop" class="form-control d-none">
                            <img src="{{ asset('icons/icon-cloud.svg') }}" alt="Upload Icon">
                            <p class="custom-txt">Click to Upload or Drag and Drop</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <p class="custom-txt text-center mt-3">Already have an account?<span class="fw-bold"> Sign in</span></p>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let dropzones = document.querySelectorAll(".dropzone"); // Ambil semua dropzone

            dropzones.forEach((dropzone) => {
                let fileInput = dropzone.querySelector("input[type='file']");

                // Ketika Klik Area Drag & Drop
                dropzone.addEventListener("click", function() {
                    fileInput.click();
                });

                // Ketika File Dipilih
                fileInput.addEventListener("change", function() {
                    handleFileUpload(fileInput.files, dropzone);
                });

                // Mencegah default behavior saat drag
                dropzone.addEventListener("dragover", function(e) {
                    e.preventDefault();
                    dropzone.style.borderColor = "#3C8EE1"; // Ubah warna border saat drag
                });

                dropzone.addEventListener("dragleave", function() {
                    dropzone.style.borderColor = "#ccc"; // Kembalikan warna border
                });

                // Ketika File Ditaruh di Dropzone
                dropzone.addEventListener("drop", function(e) {
                    e.preventDefault();
                    fileInput.files = e.dataTransfer.files; // Assign file ke input
                    handleFileUpload(fileInput.files, dropzone);
                });

                // Fungsi Upload File
                function handleFileUpload(files, dropzoneElement) {
                    if (files.length > 0) {
                        let fileName = files[0].name;
                        dropzoneElement.innerHTML =
                            `<p class="custom-txt">${fileName} uploaded successfully</p>`;
                    }
                }
            });
        });
    </script>
@endpush
