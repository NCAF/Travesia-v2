@extends('app.page')
@section('title', '- Bayar Pesanan')

@include('js.bayar')

@section('content')
<div class="untree_co-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="font-weight-bold">Detail Pesanan</h2>
            </div>
            <div class="col-12 mb-5">
                <div class="row">
                    <div class="col-12 col-md-7">
                        <div class="form" style="z-index: 1">
                            <div class="form-group">
                                <label>Kode Destinasi</label>
                                <input type="text" id="kode_destinasi" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label>Kursi Dipesan</label>
                                <div class="input-group">
                                    <input type="number" id="kursi" class="form-control" min="1" readonly>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="harga-kursi">@</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Bagasi Dipesan</label>
                                <div class="input-group">
                                    <input type="number" id="bagasi" class="form-control" min="0" readonly>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="harga-bagasi">@</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-5">
                        <div class="form" style="z-index: 1">
                            <i class="fas fa-calendar-alt mr-2"></i> <span id="hari_berangkat"></span>
                            <h3 id="destinasi" class="mt-3"></h3>
                            <div class="row mt-3">
                                <div class="col-4">Total Harga</div>
                                <div class="col-7 text-right">
                                    <b class="subtotal"></b>
                                </div>
                                <div class="col-12 mt-3">
                                    <button class="btn btn-sm btn-success w-100 btn-bayar" disabled="true"><i class="fa-solid fa-money-bill"></i> Bayar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <h2 class="font-weight-bold">Profil Pemesan</h2>
            </div>
            <div class="col-12">
                <div class="form" style="z-index: 1">
                    <div class="form-group">
                        <label>Nama Pemesan</label>
                        <input type="text" value="{{$user->nama}}" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" value="{{$user->email}}" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nomor Telepon</label>
                        <input type="text" value="{{$user->telp}}" class="form-control" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
