@extends('app.page')
@section('title', '- Destinasi '.request()->segment(2))

@include('js.detail-destinasi')

@section('content')
<div class="untree_co-section">
    <div class="container">
        <div class="row">
            <h2 class="detail-destinasi-none">Memuat...</h2>
            <div class="col-12 detail-destinasi d-none">
                <div class="card">
                    <div class="col-12">
                        <a href="javascript:void(0);" class="d-block mb-3 h-100"><img src="" alt="Destinasi {{request()->segment(2)}}" class="img-fluid w-100" id="foto-destinasi" style="height: 500px;"></a>
                    </div>
                    <div class="col-12 p-3">
                        <div class="row">
                            <div class="col-9">
                                <h3>
                                    <b><a href="javascript:void(0);">{{request()->segment(2)}}</a></b>
                                </h3>
                            </div>
                            <div class="col-3 text-right">
                                <p class="badge badge-primary" style="font-size: 15px;" id="status-destinasi"></p>
                            </div>
                        </div>
                        <h3><span id="destinasi_awal"></span> <i class="fa-solid fa-arrow-right"></i> <span id="destinasi_akhir"></span></h3>
                        <i>
                            <i class="fa-solid fa-calendar"></i> <span id="hari_berangkat"></span>
                        </i>
                        <hr>
                        <div class="row">
                            <div class="col-6 info-destinasi">

                            </div>
                            <div class="col-6 text-right">
                                <a href="{{route('home')}}/order/{{request()->segment(2)}}"><button class="btn btn-sm btn-success"><i class="fa-solid fa-clipboard-list"></i> Pesan</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
