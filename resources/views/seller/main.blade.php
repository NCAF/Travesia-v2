@extends('app.dashboard')
@section('title', 'Dashboard')

@include('seller.js.main')

@section('content')
  <div class="row">

    <div class="col-lg-12">
        <div class="row">

            <div class="col-lg-3 col-6">
                <div class="card overflow-hidden">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-9 fw-semibold">Destinasi</h5>
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h4 class="fw-semibold mb-3" id="destinasi">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="card overflow-hidden">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-9 fw-semibold">Pesanan</h5>
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h4 class="fw-semibold mb-3" id="pesanan">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="card overflow-hidden">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-9 fw-semibold">Total Pendapatan</h5>
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h4 class="fw-semibold mb-3" id="total_pendapatan">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="card overflow-hidden">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-9 fw-semibold">Pendapatan Bulan Ini</h5>
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h4 class="fw-semibold mb-3" id="pendapatan_bulan_ini">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 d-flex align-items-strech">
      <div class="card w-100">
        <div class="card-body">
          <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
            <div class="mb-3 mb-sm-0">
              <h5 class="card-title fw-semibold">Sales Overview (Hanya Demo Chart)</h5>
            </div>
            <div>
              <select class="form-select">
                <option value="1">March 2023</option>
                <option value="2">April 2023</option>
                <option value="3">May 2023</option>
                <option value="4">June 2023</option>
              </select>
            </div>
          </div>
          <div id="chart"></div>
        </div>
      </div>
    </div>
  </div>
@endsection
