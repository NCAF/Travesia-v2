@push('script')
<script>
    $(document).ready(function() {
        function load_destinasi(params = []) {
			$("table.table").DataTable().destroy()
			$("table.table").DataTable({
				"deferRender": true,
				"responsive": true,
				'serverSide': true,
				'processing': true,
				"ordering": false,
				"ajax": {
					"url": "{{ route('api.destinasi.index') }}",
					"type": "GET",
					"data": {
						"sort": "DESC"
					},
					"headers": {
						"Authorization" : getAuthorization()
					},
					"dataSrc": "data"
				},
				"columns": [
					{
						data: null,
						render: function (data, type, row, meta) {
							return meta.row + meta.settings._iDisplayStart + 1 + '.';
						}
					},
					{
						data: 'kode_destinasi'
					},
					{
						data: 'destinasi_awal'
					},
					{
						data: 'destinasi_akhir'
					},
					{
						data: 'hari_berangkat'
					},
					{
						data: null,
                        render: res => {
                            let status = ''
                            if (res.status == 'orderable') {
                                status = '<p class="badge rounded-pill bg-success">Orderable</p>'
                            } else if (res.status == 'traveling') {
                                status = '<p class="badge rounded-pill bg-warning">Traveling</p>'
                            } else {
                                status = '<p class="badge rounded-pill bg-danger">Arrived</p>'
                            }

                            return status
                        }
					},
					{
						data: null,
						render: res => {
                            let btn_sampai = ``
                            let btn_traveling = ``

                            if (res.traveling == 1) {
                                btn_traveling = `<button class="btn btn-sm mb-1 btn-success btn-perjalanan" data-id="${res.id}" data-kode_destinasi="${res.kode_destinasi}">Dalam Perjalanan</button>`
                            }

                            if (res.status == 'traveling') {
                                btn_sampai = `<button class="btn btn-sm mb-1 btn-success btn-sampai" data-id="${res.id}" data-kode_destinasi="${res.kode_destinasi}">Sampai</button>`
                                btn_traveling = ``
                            } else if (res.status == 'arrived') {
                                btn_traveling = ``
                            }


							return `
                                ${btn_sampai}
                                ${btn_traveling}
								<button class="btn btn-sm mb-1 btn-info btn-detail" data-bs-toggle="modal" data-bs-target="#detailModal"
                                    data-kode_destinasi="${res.kode_destinasi}"
                                    data-destinasi_awal="${res.destinasi_awal}"
                                    data-destinasi_akhir="${res.destinasi_akhir}"
                                    data-jenis_kendaraan="${res.jenis_kendaraan}"
                                    data-no_plat="${res.no_plat}"
                                    data-hari_berangkat="${res.hari_berangkat}"
                                    data-jumlah_kursi="${res.jumlah_kursi}"
                                    data-jumlah_bagasi="${res.jumlah_bagasi}"
                                    data-deskripsi="${res.deskripsi}"
                                    data-harga_kursi="${res.harga_kursi}"
                                    data-harga_bagasi="${res.harga_bagasi}"
                                    data-foto="${res.foto}"
                                    data-status="${res.status}"
                                >
                                    <i class="ti ti-eye"></i> Detail
                                </button><br>
                                <button class="btn btn-sm mb-1 btn-outline-dark btn-update" data-bs-toggle="modal" data-bs-target="#crudModal"
                                    data-id="${res.id}"
                                    data-kode_destinasi="${res.kode_destinasi}"
                                    data-destinasi_awal="${res.destinasi_awal}"
                                    data-destinasi_akhir="${res.destinasi_akhir}"
                                    data-jenis_kendaraan="${res.jenis_kendaraan}"
                                    data-no_plat="${res.no_plat}"
                                    data-hari_berangkat="${res.hari_berangkat}"
                                    data-jumlah_kursi="${res.jumlah_kursi}"
                                    data-jumlah_bagasi="${res.jumlah_bagasi}"
                                    data-deskripsi="${res.deskripsi}"
                                    data-harga_kursi="${res.harga_kursi}"
                                    data-harga_bagasi="${res.harga_bagasi}"
                                >
                                    <i class="ti ti-edit"></i> Edit
                                </button><br>
                                <button class="btn btn-sm mb-1 btn-outline-dark btn-delete" data-id="${res.id}" data-kode_destinasi="${res.kode_destinasi}"><i class="ti ti-trash"></i> Delete</button>
							`;
						}
					}
				]
			});
		}

		load_destinasi();

        let UploadFile = new FileUpload('#file--upload-foto',{
            accept: [
                'png',
                'jpg',
                'jpeg'
            ],
            maxSize: 1,
            maxFile: 1
        });

        $(document).on('click', '.btn-add', function () {
            $('.title-modal').html('Tambah')
            $('.btn-confirm-add').removeClass('d-none')
            $('.btn-confirm-update').addClass('d-none')
            $('.kode-destinasi').html('')

            $('#crudModal form')[0].reset()
            UploadFile.reset()
        })

        $(document).on('click', '.btn-confirm-add', function () {
            data = {
                destinasi_awal: $("input#destinasi_awal").val(),
                destinasi_akhir: $("input#destinasi_akhir").val(),
                jenis_kendaraan: $("input#jenis_kendaraan").val(),
                no_plat: $("input#no_plat").val(),
                hari_berangkat: moment($("input#hari_berangkat").val()).format('YYYY-MM-DD HH:mm:ss'),
                jumlah_kursi: $("input#jumlah_kursi").val(),
                jumlah_bagasi: $("input#jumlah_bagasi").val(),
                deskripsi: $("textarea#deskripsi").val(),
                harga_kursi: $("input#harga_kursi").val(),
                harga_bagasi: $("input#harga_bagasi").val(),
                foto: UploadFile.getFiles(),
            }

            callApi("POST", "{{ route('api.destinasi.store') }}", data, function (req) {
                pesan = req.message;
                if (req.error == true) {
                    Swal.fire(
                    'Gagal ditambahkan!',
                    pesan,
                    'error'
                    )
                }else{
                    Swal.fire(
                    'Ditambahkan!',
                    pesan,
                    'success'
                    )
                    $("#crudModal").modal("hide")
                    load_destinasi();
                }
            })
        })

        $(document).on('click', ".btn-update", function () {
            $('.title-modal').html('Edit')
            $('.btn-confirm-update').removeClass('d-none')
            $('.btn-confirm-add').addClass('d-none')
            $('.kode-destinasi').html($(this).attr('data-kode_destinasi'))

            $('#crudModal input#destinasi_awal').val($(this).attr('data-destinasi_awal'))
            $('#crudModal input#destinasi_akhir').val($(this).attr('data-destinasi_akhir'))
            $('#crudModal input#jenis_kendaraan').val($(this).attr('data-jenis_kendaraan'))
            $('#crudModal input#no_plat').val($(this).attr('data-no_plat'))
            $('#crudModal input#hari_berangkat').val($(this).attr('data-hari_berangkat'))
            $('#crudModal input#jumlah_kursi').val($(this).attr('data-jumlah_kursi'))
            $('#crudModal input#jumlah_bagasi').val($(this).attr('data-jumlah_bagasi'))
            $('#crudModal textarea#deskripsi').val($(this).attr('data-deskripsi'))
            $('#crudModal input#harga_kursi').val($(this).attr('data-harga_kursi'))
            $('#crudModal input#harga_bagasi').val($(this).attr('data-harga_bagasi'))

            $('.btn-confirm-update').attr('data-id', $(this).attr('data-id'))
            UploadFile.reset()
        })

        $(document).on('click', '.btn-confirm-update', function () {
            data = {
                destinasi_awal: $("input#destinasi_awal").val(),
                destinasi_akhir: $("input#destinasi_akhir").val(),
                jenis_kendaraan: $("input#jenis_kendaraan").val(),
                no_plat: $("input#no_plat").val(),
                hari_berangkat: moment($("input#hari_berangkat").val()).format('YYYY-MM-DD HH:mm:ss'),
                jumlah_kursi: $("input#jumlah_kursi").val(),
                jumlah_bagasi: $("input#jumlah_bagasi").val(),
                deskripsi: $("textarea#deskripsi").val(),
                harga_kursi: $("input#harga_kursi").val(),
                harga_bagasi: $("input#harga_bagasi").val(),
                foto: UploadFile.getFiles(),
            }

            let id = $(this).attr('data-id');

            let url = "{{ route('api.destinasi.update', ':id') }}";
            url = url.replace(':id', id);

            callApi("PUT", url, data, function (req) {
                pesan = req.message;
                if (req.error == true) {
                    Swal.fire(
                    'Gagal diupdate!',
                    pesan,
                    'error'
                    )
                }else{
                    Swal.fire(
                    'Diupdate!',
                    pesan,
                    'success'
                    )
                    $("#crudModal").modal("hide")
                    load_destinasi();
                }
            })
        })

        $(document).on('click', ".btn-delete", function () {
            let id = $(this).attr('data-id')
            let kode_destinasi = $(this).attr('data-kode_destinasi')

            Swal.fire({
            title: 'Apakah anda yakin?',
            text: `Anda ingin menghapus data ${kode_destinasi}!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
            if (result.isConfirmed) {
                let url = "{{ route('api.destinasi.destroy', ':id') }}";
                url = url.replace(':id', id);
                callApi("DELETE", url, [], function (req) {
                    pesan = req.message;
                    if (req.error == true) {
                        Swal.fire(
                        'Gagal Dihapus!',
                        pesan,
                        'error'
                        )
                    }else{
                        Swal.fire(
                        'Dihapus!',
                        pesan,
                        'success'
                        )
                        load_destinasi();
                    }
                })

            }
            })
        })

        $(document).on('click', ".btn-perjalanan", function () {
            let id = $(this).attr('data-id')
            let kode_destinasi = $(this).attr('data-kode_destinasi')

            Swal.fire({
            title: 'Apakah anda yakin?',
            text: `Anda ingin mengubah status ${kode_destinasi} menjadi 'traveling'?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya!'
            }).then((result) => {
            if (result.isConfirmed) {
                let url = "{{ route('api.destinasi.traveling', ':id') }}";
                url = url.replace(':id', id);
                callApi("POST", url, [], function (req) {
                    pesan = req.message;
                    if (req.error == true) {
                        Swal.fire(
                        'Gagal Diubah!',
                        pesan,
                        'error'
                        )
                    }else{
                        Swal.fire(
                        'Diubah!',
                        pesan,
                        'success'
                        )
                        load_destinasi();
                    }
                })

            }
            })
        })

        $(document).on('click', ".btn-sampai", function () {
            let id = $(this).attr('data-id')
            let kode_destinasi = $(this).attr('data-kode_destinasi')

            Swal.fire({
            title: 'Apakah anda yakin?',
            text: `Anda ingin mengubah status ${kode_destinasi} menjadi 'arrived'?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya!'
            }).then((result) => {
            if (result.isConfirmed) {
                let url = "{{ route('api.destinasi.arrived', ':id') }}";
                url = url.replace(':id', id);
                callApi("POST", url, [], function (req) {
                    pesan = req.message;
                    if (req.error == true) {
                        Swal.fire(
                        'Gagal Diubah!',
                        pesan,
                        'error'
                        )
                    }else{
                        Swal.fire(
                        'Diubah!',
                        pesan,
                        'success'
                        )
                        load_destinasi();
                    }
                })

            }
            })
        })

        $(document).on('click', ".btn-detail", function () {
            $('.kode-destinasi').html($(this).attr('data-kode_destinasi'))

            $('#detail-foto').attr('src', "{{ url('') }}"+$(this).attr('data-foto'))

            $('#detail-destinasi_awal').html($(this).attr('data-destinasi_awal'))
            $('#detail-destinasi_akhir').html($(this).attr('data-destinasi_akhir'))
            $('#detail-jenis_kendaraan').html($(this).attr('data-jenis_kendaraan'))
            $('#detail-no_plat').html($(this).attr('data-no_plat'))
            $('#detail-hari_berangkat').html($(this).attr('data-hari_berangkat'))
            $('#detail-jumlah_kursi').html($(this).attr('data-jumlah_kursi'))
            $('#detail-jumlah_bagasi').html($(this).attr('data-jumlah_bagasi'))
            $('#detail-deskripsi').html($(this).attr('data-deskripsi'))
            $('#detail-harga_kursi').html($(this).attr('data-harga_kursi'))
            $('#detail-harga_bagasi').html($(this).attr('data-harga_bagasi'))

            let status = ''
            if ($(this).attr('data-status') == 'orderable') {
                status = '<p class="ms-2 badge rounded-pill bg-success">Orderable</p>'
            } else if ($(this).attr('data-status') == 'traveling') {
                status = '<p class="ms-2 badge rounded-pill bg-warning">Traveling</p>'
            } else {
                status = '<p class="ms-2 badge rounded-pill bg-danger">Arrived</p>'
            }

            $('#detail-status').html(status)
        })
    })
</script>
@endpush
