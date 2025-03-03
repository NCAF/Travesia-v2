@extends('app.page')
@section('title', '')

@include('js.home')

@section('content')
<div class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="intro-wrap">
                    <h1 class="mb-5"><span class="d-block">Mari Nikmati Perjalanan Anda</span> Di <span class="typed-words"></span></h1>

                    <div class="row">
                        <div class="col-12">
                            <form class="form">
                                <div class="row mb-2">
                                    <div class="col-sm-12 col-md-6 mb-3 mb-lg-0 col-lg-3">
                                        <input type="text" id="dari" class="form-control" placeholder="Dari">
                                    </div>
                                    <div class="col-sm-12 col-md-6 mb-3 mb-lg-0 col-lg-3">
                                        <input type="text" id="tujuan" class="form-control" placeholder="Tujuan">
                                    </div>
                                    <div class="col-sm-12 col-md-6 mb-3 mb-lg-0 col-lg-6">
                                        <input type="date" id="tanggal" class="form-control">
                                    </div>

                                </div>
                                <div class="row align-items-center">
                                    <div class="col-sm-12 col-md-6 mb-3 mb-lg-0 col-lg-4">
                                        <input type="button" class="btn btn-primary btn-block btn-search-destinasi" value="Search">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="slides">
                    <img src="{{url('assets/images/hero-slider-1.jpg')}}" alt="Image" class="img-fluid active">
                    <img src="{{url('assets/images/hero-slider-2.jpg')}}" alt="Image" class="img-fluid">
                    <img src="{{url('assets/images/hero-slider-3.jpg')}}" alt="Image" class="img-fluid">
                    <img src="{{url('assets/images/hero-slider-4.jpg')}}" alt="Image" class="img-fluid">
                    <img src="{{url('assets/images/hero-slider-5.jpg')}}" alt="Image" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="untree_co-section">
    <div class="container">
        <div class="row text-center justify-content-center mb-5">
            <div class="col-lg-7"><h2 class="section-title text-center">Recent Destination</h2></div>
        </div>

        <div class="recent-destination">

        </div>

    </div>
</div>

<div class="untree_co-section">
    <div class="container">
        <div class="row mb-5 justify-content-center">
            <div class="col-lg-6 text-center">
                <h2 class="section-title text-center mb-3">Our Services</h2>
            </div>
        </div>
        <div class="row align-items-stretch">
            <div class="col-lg-4 order-lg-1">
                <div class="h-100"><div class="frame h-100"><div class="feature-img-bg h-100" style="background-image: url('../assets/images/hero-slider-1.jpg');"></div></div></div>
            </div>

            <div class="col-6 col-sm-6 col-lg-4 feature-1-wrap d-md-flex flex-md-column order-lg-1" >

                <div class="feature-1 d-md-flex">
                    <div class="align-self-center">
                        <span class="flaticon-house display-4 text-primary"></span>
                        <h3>Beautiful Condo</h3>
                        <p class="mb-0">Even the all-powerful Pointing has no control about the blind texts.</p>
                    </div>
                </div>

                <div class="feature-1 ">
                    <div class="align-self-center">
                        <span class="flaticon-restaurant display-4 text-primary"></span>
                        <h3>Restaurants & Cafe</h3>
                        <p class="mb-0">Even the all-powerful Pointing has no control about the blind texts.</p>
                    </div>
                </div>

            </div>

            <div class="col-6 col-sm-6 col-lg-4 feature-1-wrap d-md-flex flex-md-column order-lg-3" >

                <div class="feature-1 d-md-flex">
                    <div class="align-self-center">
                        <span class="flaticon-mail display-4 text-primary"></span>
                        <h3>Easy to Connect</h3>
                        <p class="mb-0">Even the all-powerful Pointing has no control about the blind texts.</p>
                    </div>
                </div>

                <div class="feature-1 d-md-flex">
                    <div class="align-self-center">
                        <span class="flaticon-phone-call display-4 text-primary"></span>
                        <h3>24/7 Support</h3>
                        <p class="mb-0">Even the all-powerful Pointing has no control about the blind texts.</p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>


@endsection
