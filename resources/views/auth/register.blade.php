@extends('layouts.app')
@section('title', 'Register Page')
@push('styles')
    <style>
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
        
        
        <div class="row justify-content-center">
            <div class="col-md-4">
                @if(session('success'))
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    </div>
                </div>
                @endif
                @if(session('error'))
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    </div>
                </div>
                @endif
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="role" value="user">
                    
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" class="custom-input form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama') }}" placeholder="John Doe">
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="custom-input form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="your_email@example.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" 
                            class="custom-input form-control @error('password') is-invalid @enderror" placeholder="********">
                        <small class="form-text text-muted">Minimal 8 karakter, harus mengandung huruf besar, huruf kecil, dan angka</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                            class="custom-input form-control" placeholder="********">
                    </div>
                    
                    <button type="submit" class="custom-btn btn w-100 fw-bold">Sign Up</button>
                </form>
            </div>
        </div>
        <p class="custom-txt text-center mt-3">Already have an account? <a href="{{ route('login') }}" class="fw-bold">Sign in</a></p>
    </div>
@endsection
