@extends('app.page')
@section('title', '- Pesanan')

@include('js.pesanan')

@section('content')
<div class="hero hero-inner">
    <div class="container">
    <div class="row align-items-center">
        <div class="col-lg-6 mx-auto text-center">
        <div class="intro-wrap">
            <h1 class="mb-0">Pesanan</h1>
        </div>
        </div>
    </div>
    </div>
</div>

<div class="untree_co-section">

    <section class="light py-3">
        <div class="container py-2 mb-2">
            <div class="row">
                <div class="col-1">
                    <div class="bg-primary rounded-pill text-center text-white d-flex align-items-center justify-content-center w-100 h-100">
                        <i class="fa-solid fa-search"></i>
                    </div>
                </div>
                <div class="col-11">
                    <input type="text" class="form-control search-order" placeholder="Search...">
                </div>
            </div>
        </div>
        <div class="container py-2 list-pesanan">
            <div class="h1 text-center text-dark" id="pageHeaderTitle">Memuat Pesanan...</div>
        </div>
        <div class="container py-2 text-center btn-more-section d-none">
            <button class="btn btn-lg btn-primary btn-more"><i class="fa-solid fa-arrow-down"></i> Lihat Lebih Banyak</button>
        </div>
    </section>

</div>

@endsection
