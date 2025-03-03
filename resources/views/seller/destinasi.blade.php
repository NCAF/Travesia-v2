@extends('app.dashboard')
@section('title', 'Destinasi')

@include('seller.js.destinasi')

@section('content')
<div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title fw-semibold mb-4">Destinasi</h5>

        <div class="row">
            <div class="col-12 text-end">
                <button type="button" class="btn btn-primary m-1 btn-add" data-bs-toggle="modal" data-bs-target="#crudModal">
                    <i class="ti ti-plus"></i>
                    Tambah Destinasi
                </button>
            </div>

            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Kode Destinasi</th>
                                <th scope="col">Destinasi Awal</th>
                                <th scope="col">Destinasi Akhir</th>
                                <th scope="col">Keberangkatan</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

      </div>
    </div>
</div>

<!-- CRUD Modal -->
<div class="modal modal-lg fade" id="crudModal" tabindex="-1" aria-labelledby="crudModalTitle" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="crudModalTitle"><span class="title-modal"></span> Destinasi <span class="kode-destinasi"></span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form>
                <div class="row">
                    <div class="mb-3 col-12">
                        <h3><b>Rute</b></h3>
                    </div>
                    <div class="mb-3 col-6">
                      <label class="form-label">Destinasi Awal</label>
                      <input type="text" class="form-control" id="destinasi_awal">
                    </div>
                    <div class="mb-3 col-6">
                      <label class="form-label">Destinasi Akhir</label>
                      <input type="text" class="form-control" id="destinasi_akhir">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-12">
                        <h3><b>Kendaraan</b></h3>
                    </div>
                    <div class="mb-3 col-6">
                      <label class="form-label">Jenis Kendaraan</label>
                      <input type="text" class="form-control" id="jenis_kendaraan">
                    </div>
                    <div class="mb-3 col-6">
                      <label class="form-label">No. PLAT</label>
                      <input type="text" class="form-control" id="no_plat">
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">Jumlah Kursi</label>
                        <input type="number" class="form-control" id="jumlah_kursi">
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">Harga per Kursi</label>
                        <input type="text" class="form-control" id="harga_kursi">
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">Jumlah Bagasi</label>
                        <input type="number" class="form-control" id="jumlah_bagasi">
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">Harga per Bagasi</label>
                        <input type="text" class="form-control" id="harga_bagasi">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-12">
                        <h3><b>Lain-lain</b></h3>
                    </div>
                    <div class="mb-3 col-12">
                      <label class="form-label">Waktu Berangkat</label>
                      <input type="datetime-local" class="form-control" id="hari_berangkat">
                    </div>
                    <div class="mb-3 col-12">
                      <label class="form-label">Deskripsi</label>
                      <textarea id="deskripsi" class="form-control"></textarea>
                    </div>
                    <div class="mb-3 col-12">
                      <label class="form-label">Sampul Destinasi</label>
                      <div id="file--upload-foto"></div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer row">
            <button type="button" class="btn btn-primary col-6 d-none btn-confirm-update">Update</button>
            <button type="button" class="btn btn-primary col-6 d-none btn-confirm-add">Tambah</button>
        </div>
      </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal modal-lg fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalTitle" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="card w-100">
                <img id="detail-foto" class="card-img-top" alt="Sampul Destinasi">
                <div class="card-body">
                  <h5 class="card-title mb-3">Destinasi <span class="kode-destinasi"></span> <span id="detail-status"></span></h5>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <p class="mb-1"><strong>Destinasi Awal:</strong></p>
                      <p class="text-muted" id="detail-destinasi_awal"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                      <p class="mb-1"><strong>Destinasi Akhir:</strong></p>
                      <p class="text-muted" id="detail-destinasi_akhir"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                      <p class="mb-1"><strong>Jenis Kendaraan:</strong></p>
                      <p class="text-muted" id="detail-jenis_kendaraan"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                      <p class="mb-1"><strong>No. Plat:</strong></p>
                      <p class="text-muted" id="detail-no_plat"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                      <p class="mb-1"><strong>Waktu Berangkat:</strong></p>
                      <p class="text-muted" id="detail-hari_berangkat"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                      <p class="mb-1"><strong>Jumlah Kursi:</strong></p>
                      <p class="text-muted" id="detail-jumlah_kursi"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                      <p class="mb-1"><strong>Harga Kursi:</strong></p>
                      <p class="text-muted" id="detail-harga_kursi"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                      <p class="mb-1"><strong>Jumlah Bagasi:</strong></p>
                      <p class="text-muted" id="detail-jumlah_bagasi"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                      <p class="mb-1"><strong>Harga Bagasi:</strong></p>
                      <p class="text-muted" id="detail-harga_bagasi"></p>
                    </div>
                    <div class="col-12 mb-3">
                      <p class="mb-1"><strong>Deskripsi:</strong></p>
                      <p class="text-muted" id="detail-deskripsi"></p>
                    </div>
                  </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
@endsection
