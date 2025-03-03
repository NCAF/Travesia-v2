@push("script")
<script>
    $(document).ready(function() {
        let page = 1
        function get_pesanan(search = '', page = '') {
            var params = {
                search: search,
                page: page
            }

            let queryParams = buildQueryString(params)

            callApi("GET", "{{ route('api.orders.user') }}"+queryParams, {}, function (req) {
                list = '';
                $.each(req.data, function (index, val) {
                    let status = ''
                    if (val.status == 'order') {
                        status = `<div class="col text-right">
                                        <a href="{{url('bayar')}}/${val.order_id}" class="btn btn-sm btn-success text-white"><i class="fa-solid fa-credit-card"></i> Bayar</a>
                                </div>`
                    }

                    list += `
                    <article class="postcard light blue">
                        <a class="postcard__img_link" href="javascript:void(0);">
                            <img class="postcard__img" src="{{url('')}}${val.foto}" alt="Image Title" />
                        </a>
                        <div class="postcard__text t-dark">
                            <h1 class="postcard__title">
                                <a href="javascript:void(0);">
                                    ${val.kode_destinasi}
                                </a>
                            </h1>
                            <div class="postcard__subtitle small">
                                <time datetime="${val.hari_berangkat}">
                                    <i class="fas fa-calendar-alt mr-2"></i>${val.hari_berangkat}
                                </time>
                            </div>
                            <div class="postcard__bar"></div>
                            <ul class="postcard__tagbox mt-0 mb-2">
                                <li class="tag__item"><i class="fa-solid fa-circle-exclamation"></i> ${val.status}</li>
                            </ul>
                            <div class="postcard__preview-txt">
                                <div class="row">
                                    <div class="col">
                                        <h5>${val.destinasi_awal} &nbsp; <i class="fa-solid fa-arrow-right"></i> &nbsp; ${val.destinasi_akhir}</h5>
                                    </div>
                                    ${status}
                                </div>
                                <hr>
                                <p>
                                    <b>Kursi: </b>&nbsp; ${val.jumlah_kursi} &nbsp; (Rp. ${toIdr(val.harga_kursi)})
                                </p>
                                <p>
                                    <b>Bagasi: </b>&nbsp; ${val.jumlah_bagasi} &nbsp; (Rp. ${toIdr(val.harga_bagasi)})
                                </p>
                                <p>
                                    <b>Total: &nbsp; Rp. ${toIdr(val.subtotal)}</b>
                                </p>
                            </div>
                        </div>
                    </article>
                    `;
                });

                if (page!='') {
                    $(".list-pesanan").append(list);
                } else {
                    $(".list-pesanan").html(list);
                }

                if (req.data.length == 0) {
                    $(".list-pesanan").html('<div class="h1 text-center text-dark" id="pageHeaderTitle">Tidak Ada Pesanan!</div>')
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

        get_pesanan();

        $('.btn-more').on('click', function(){
            $('.btn-more').attr('disabled', true)
            $('.btn-more').html(`<i class="fa-solid fa-spinner fa-spin"></i> Lihat Lebih Banyak`)
            get_pesanan($('.search-order').val(), page)
            page = page+1
        })

        $('.search-order').on('keyup', function(){
            page = 1
            get_pesanan($(this).val(),'')
        })
    })
</script>
@endpush
