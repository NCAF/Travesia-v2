@extends('app.dashboard')
@section('title', 'Pesanan')

@include('seller.js.pesanan')

@section('content')
<div class="container-fluid">
    <div class="card">
      <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Pesanan</h5>

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">ID Order</th>
                                    <th scope="col">Pemesan</th>
                                    <th scope="col">Destinasi</th>
                                    <th scope="col">Pesanan</th>
                                    <th scope="col">Subtotal</th>
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
@endsection
