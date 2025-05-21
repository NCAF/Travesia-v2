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

        @if(session('error'))
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            </div>
        </div>
        @endif

        @if(session('success'))
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            </div>
        </div>
        @endif
        
        
        <form method="POST" action="{{ route('driver.register-driver.post') }}" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-center">
                <!-- Kolom Input Form -->
                <div class="col-md-4 order-md-1 order-1">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name" class="custom-input form-control @error('name') is-invalid @enderror" placeholder="your name" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="custom-input form-control @error('email') is-invalid @enderror"
                            placeholder="your_email@example.com" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="custom-input form-control @error('password') is-invalid @enderror" placeholder="********">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="custom-input form-control @error('password_confirmation') is-invalid @enderror"
                            placeholder="********">
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Input File (Licensi) Dipindah ke Sini di Mobile -->
                    <div class="mb-3 d-md-none">
                        <label for="image" class="form-label">Licensi</label>
                        <div class="drag-drop dropzone @error('image') border-danger @enderror">
                            <input type="file" id="image" name="image" class="form-control">
                            <img src="{{ asset('icons/icon-cloud.svg') }}" alt="Upload Icon">
                            <p class="custom-txt">Click to Upload or Drag and Drop</p>
                        </div>
                        @error('image')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Button Sign Up -->
                    <button type="submit" class="custom-btn btn w-100 fw-bold">Sign Up</button>
                </div>

                <!-- Kolom Input File untuk Desktop -->
                <div class="col-md-4 order-md-2 order-2 d-none d-md-block">
                    <div class="mb-3">
                        <label for="image-desktop" class="form-label">Licensi</label>
                        <div class="drag-drop dropzone @error('image') border-danger @enderror">
                            <input type="file" id="image-desktop" name="image" class="form-control">
                            <img src="{{ asset('icons/icon-cloud.svg') }}" alt="Upload Icon">
                            <p class="custom-txt">Click to Upload or Drag and Drop</p>
                        </div>
                        @error('image')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </form>
        <p class="custom-txt text-center mt-3">Already have an account? <a href="{{ route('login') }}" class="fw-bold">Sign in</a></p>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let dropzones = document.querySelectorAll(".dropzone"); // Ambil semua dropzone

            dropzones.forEach((dropzone) => {
                let fileInput = dropzone.querySelector("input[type='file']");
                let preview = dropzone.querySelector("img");
                let textElement = dropzone.querySelector("p");

                // Ketika Klik Area Drag & Drop
                dropzone.addEventListener("click", function(e) {
                    // Prevent click from being triggered on the input itself
                    if (e.target !== fileInput) {
                        fileInput.click();
                    }
                });

                // Ketika File Dipilih
                fileInput.addEventListener("change", function() {
                    handleFileUpload(fileInput.files, dropzone, preview, textElement);
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
                    handleFileUpload(fileInput.files, dropzone, preview, textElement);
                });

                // Fungsi Upload File
                function handleFileUpload(files, dropzoneElement, preview, textElement) {
                    if (files.length > 0) {
                        let fileName = files[0].name;
                        
                        // Show preview if it's an image
                        if (files[0].type.startsWith('image/')) {
                            let reader = new FileReader();
                            reader.onload = function(e) {
                                preview.src = e.target.result;
                                textElement.textContent = fileName;
                            };
                            reader.readAsDataURL(files[0]);
                        } else {
                            textElement.textContent = fileName + " uploaded";
                        }
                    }
                }
            });
        });
    </script>
@endpush
