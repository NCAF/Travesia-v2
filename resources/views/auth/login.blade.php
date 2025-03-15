@extends('layouts.app')
@section('title', 'Login Page')
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
                <h1>Welcome Back</h1>
            </div>
        </div>
        <div class="row text-center mt-3">
            <div class="col-md">
                <p class="fw-normal custom-txt">Sign in to continue your adventure.</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <form>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" class="custom-input form-control"
                            placeholder="your_email@example.com">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" class="custom-input form-control" placeholder="********">
                    </div>
                    <button type="submit" class="custom-btn btn w-100 fw-bold">Sign In</button>
                </form>
            </div>
        </div>
        <p class="fw-normal text-center mt-3 custom-txt">Don't have an account?<span class="fw-bold"> Sign Up</span></p>
    </div>
@endsection
