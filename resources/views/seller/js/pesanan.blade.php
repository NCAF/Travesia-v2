@push('script')
<script>
    $(document).ready(function() {
        function load_order(params = []) {
			$("table.table").DataTable().destroy()
			$("table.table").DataTable({
				"deferRender": true,
				"responsive": true,
				'serverSide': true,
				'processing': true,
				"ordering": false,
				"ajax": {
					"url": "{{ route('api.orders.seller') }}",
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
                        data: 'order_id'
                    },
                    {
                        data: 'nama'
                    },
					{
						data: null,
                        render: res => {
                            return `
                                ${res.kode_destinasi}<br>
                                ${res.destinasi_awal} <i class="ti ti-arrow-big-right"></i> ${res.destinasi_akhir}
                            `
                        }
					},
					{
						data: null,
                        render: res => {
                            return `
                                <b>Kursi:</b> <br><b>${res.jumlah_kursi}</b> (Rp. ${toIdr(res.harga_kursi)})<br class="mb-2">
                                <b>Bagasi:</b> <br><b>${res.jumlah_bagasi}</b> (Rp. ${toIdr(res.harga_bagasi)})
                            `
                        }
					},
					{
						data: null,
                        render: res => {
                            return `Rp. ${toIdr(res.subtotal)}`
                        }
					},
					{
						data: null,
                        render: res => {
                            let status = ''
                            if (res.status == 'order') {
                                status = '<p class="badge badge-sm rounded-pill bg-info">'+res.status+'</p>'
                            } else if (res.status == 'paid') {
                                status = '<p class="badge badge-sm rounded-pill bg-success">'+res.status+'</p>'
                            } else if (res.status == 'finished') {
                                status = '<p class="badge badge-sm rounded-pill bg-success">'+res.status+'</p>'
                            } else {
                                status = '<p class="badge badge-sm rounded-pill bg-danger">'+res.status+'</p>'
                            }

                            return status
                        }
					},
					{
						data: null,
						render: res => {
                            let button = '-'

                            if (res.status == 'paid') {
                                button = `<button class="btn btn-sm mb-1 btn-outline-dark btn-finished" data-id="${res.id}" data-kode_destinasi="${res.kode_destinasi}" data-user="${res.nama}"><i class="ti ti-circle-check"></i> Selesaikan</button>`
                            }

							return button;
						}
					}
				]
			});
		}

		load_order();

        $(document).on('click', ".btn-finished", function () {
            let id = $(this).attr('data-id')
            let kode_destinasi = $(this).attr('data-kode_destinasi')
            let user = $(this).attr('data-user')

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: `Anda ingin menyelesaikan pesanan ${kode_destinasi} oleh ${user}!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#13deb9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Selesaikan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('api.orders.finished', ':id') }}";
                    url = url.replace(':id', id);
                    callApi("POST", url, [], function (req) {
                        pesan = req.message;
                        if (req.error == true) {
                            Swal.fire(
                            'Gagal Diselesaikan!',
                            pesan,
                            'error'
                            )
                        }else{
                            Swal.fire(
                            'Selesai!',
                            pesan,
                            'success'
                            )
                            load_order();
                        }
                    })

                }
            })
        })
    })
</script>
@endpush
