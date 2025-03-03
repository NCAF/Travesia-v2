@extends('app.page')
@section('title', '- Destinasi')

@include('js.destinasi')

@section('content')
<div class="hero hero-inner">
    <div class="container">
    <div class="row align-items-center">
        <div class="col-lg-6 mx-auto text-center">
        <div class="intro-wrap">
            <h1 class="mb-0">Destinasi</h1>
        </div>
        </div>
    </div>
    </div>
</div>

<div class="untree_co-section">
    <div class="container py-2 mb-5">
        <div class="row">
            <div class="col-12">
                <form class="form" style="z-index: 1">
                    <div class="row mb-2">
                        <div class="col-sm-12 col-md-6 mb-3 mb-lg-0 col-lg-3">
                            <input type="text" value="{{request()->get('dari')}}" id="dari" class="form-control" placeholder="Dari">
                        </div>
                        <div class="col-sm-12 col-md-6 mb-3 mb-lg-0 col-lg-3">
                            <input type="text" value="{{request()->get('tujuan')}}" id="tujuan" class="form-control" placeholder="Tujuan">
                        </div>
                        <div class="col-sm-12 col-md-6 mb-3 mb-lg-0 col-lg-6">
                            <input type="date" value="{{request()->get('tanggal')}}" id="tanggal" class="form-control">
                        </div>

                    </div>
                    <div class="row align-items-center">
                        <div class="col-12 mb-3 mt-3">
                            <input type="button" class="btn btn-primary btn-block btn-search-destinasi" value="Search">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row list-destinasi">
            <h1>Memuat...</h1>
        </div>
    </div>
    <div class="container py-2 text-center btn-more-section mt-2 d-none">
        <button class="btn btn-lg btn-primary btn-more"><i class="fa-solid fa-arrow-down"></i> Lihat Lebih Banyak</button>
    </div>
</div>
@endsection
