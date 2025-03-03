@push("script")
<script>
    $(document).ready(function() {
        let page = 1
        function get_destinasi(page = '') {
            var params = {
                dari: '{{request()->input("dari")}}',
                tujuan: '{{request()->input("tujuan")}}',
                tanggal: '{{request()->input("tanggal")}}',
                page: page
            }

            let queryParams = buildQueryString(params)
            callApi("GET", "{{ route('api.destinasi.all') }}"+queryParams, {}, function (req) {
                list = '';
                $.each(req.data, function (index, val) {
                    list += `
                    <div class="col-12 col-lg-6">
                        <div class="card">
                            <div class="media-1 row" style="height: 300px">
                                <div class="col-5">
                                    <a href="{{route('destinasi')}}/${val.kode_destinasi}" class="d-block mb-3 h-100"><img src="{{url('')}}${val.foto}" alt="Image" class="img-fluid w-100 h-100"></a>
                                </div>
                                <div class="col-7 w-100 p-2">
                                    <div class="d-flex">
                                        <div>
                                            <div class="row">
                                                <div class="col-9">
                                                    <h3>
                                                        <b><a href="{{route('destinasi')}}/${val.kode_destinasi}">${val.kode_destinasi}</a></b>
                                                    </h3>
                                                </div>
                                                <div class="col-3 text-right">
                                                    <p class="badge badge-primary" id="status-destinasi">${val.status}</p>
                                                </div>
                                            </div>
                                            <h3>${val.destinasi_awal} <i class="fa-solid fa-arrow-right"></i> ${val.destinasi_akhir}</h3>
                                            <i>
                                                <i class="fa-solid fa-calendar"></i> ${val.hari_berangkat}
                                            </i>
                                            <hr>
                                            <div class="row">
                                                <div class="col-6">
                                                    <b>${val.jenis_kendaraan} (${val.no_plat})</b><br class="mb-2">
                                                    <b>Kursi Tersisa:</b> <br><b>${val.kursi_tersisa}</b> (Rp. ${toIdr(val.harga_kursi)})<br class="mb-2">
                                                    <b>Bagasi Tersisa:</b> <br><b>${val.bagasi_tersisa}</b> (Rp. ${toIdr(val.harga_bagasi)})
                                                </div>
                                                <div class="col-6 text-right">
                                                    <a href="{{route('destinasi')}}/${val.kode_destinasi}"><button class="btn btn-sm btn-primary mb-3"><i class="fa-solid fa-circle-info"></i> Detail</button></a> <br class="mb-2">
                                                    <a href="{{route('home')}}/order/${val.kode_destinasi}"><button class="btn btn-sm btn-success"><i class="fa-solid fa-clipboard-list"></i> Pesan</button></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;
                });
                if (page!='') {
                    $(".list-destinasi").append(list);
                } else {
                    $(".list-destinasi").html(list);
                }

                if (req.data.length <= 0) {
                    $(".list-destinasi").html(`<div class="h1 text-center text-dark" id="pageHeaderTitle">Tidak Ada Destinasi!</div>`);
                }

                if (!req.end) {
                    $('.btn-more-section').removeClass('d-none')
                } else {
                    $('.btn-more-section').addClass('d-none')
                }
                $('.btn-more').attr('disabled', false)
                $('.btn-more').html(`<i class="fa-solid fa-arrow-down"></i> Lihat Lebih Banyak`)
            })
        }

        get_destinasi();

        $('.btn-more').on('click', function(){
            $('.btn-more').attr('disabled', true)
            $('.btn-more').html(`<i class="fa-solid fa-spinner fa-spin"></i> Lihat Lebih Banyak`)
            get_destinasi(page)
            page = page+1
        })

        $('.btn-search-destinasi').on('click', function(e){
            e.preventDefault()
            let url = `{{url('')}}/destinasi?dari=${$('#dari').val()}&tujuan=${$('#tujuan').val()}&tanggal=${$('#tanggal').val()}`
            window.location.href = url
        })
    })
</script>
@endpush
