@push("script")
<script src="{{url('assets/js/typed.js')}}"></script>
<script>
    $(function() {
        var slides = $('.slides'),
        images = slides.find('img');

        images.each(function(i) {
            $(this).attr('data-id', i + 1);
        })

        var typed = new Typed('.typed-words', {
            strings: ["Jawa Timur."," Jawa Barat."," Jawa Tengah.", " Banten.", " Bali."],
            typeSpeed: 80,
            backSpeed: 80,
            backDelay: 4000,
            startDelay: 1000,
            loop: true,
            showCursor: true,
            preStringTyped: (arrayPos, self) => {
                arrayPos++;
                console.log(arrayPos);
                $('.slides img').removeClass('active');
                $('.slides img[data-id="'+arrayPos+'"]').addClass('active');
            }

        });
    })

    $(document).ready(function() {
        function get_recent_destinasi() {
            callApi("GET", "{{ route('api.destinasi.recent') }}", {}, function (req) {
                list = '<div class="owl-carousel owl-3-slider">';
                $.each(req.data, function (index, val) {
                    list += `
                    <div class="item" style="height: 400px; object-fit: contain;">
                        <a class="media-thumb h-100 d-flex align-item-center justify-content-center" href="{{url('')}}${val.foto}" data-fancybox="gallery">
                            <div class="media-text">
                                <h3 class="mb-2">${val.kode_destinasi}</h3>
                                <span class="location">
                                    <b>
                                        ${val.destinasi_awal} <i class="fa-solid fa-arrow-right"></i> ${val.destinasi_akhir}
                                        <br>
                                        Harga Kursi: Rp. ${toIdr(val.harga_kursi)}
                                    </b>
                                </span>
                            </div>
                            <img src="{{url('')}}${val.foto}" alt="Image" class="img-fluid">
                        </a>
                    </div>
                    `;
                });
                list += '</div>'
                $(".recent-destination").html(list);

                $('.owl-3-slider').owlCarousel({
                    loop: true,
                    autoHeight: true,
                    margin: 10,
                    autoplay: true,
                    smartSpeed: 700,
                    items: 1,
                    nav: true,
                    dots: true,
                    navText: ['<span class="icon-keyboard_backspace"></span>','<span class="icon-keyboard_backspace"></span>'],
                    responsive:{
                        0:{
                            items:1
                        },
                        600:{
                            items:1
                        },
                        800: {
                            items:2
                        },
                        1000:{
                            items:2
                        },
                        1100:{
                            items:3
                        }
                    }
                });
            })
        }

        get_recent_destinasi();

        $('.btn-search-destinasi').on('click', function(e){
            e.preventDefault()
            let url = `{{url('')}}/destinasi?dari=${$('#dari').val()}&tujuan=${$('#tujuan').val()}&tanggal=${$('#tanggal').val()}`
            window.location.href = url
        })
    })
</script>
@endpush
