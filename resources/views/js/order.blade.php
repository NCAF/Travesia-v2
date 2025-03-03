@push("script")
<script>
    $(document).ready(function() {
        var harga_kursi = 0
        var harga_bagasi = 0
        var destinasi_id = 0
        function get_detail_destinasi() {
            let url = "{{ route('api.destinasi.show', ':destinasi') }}";
            url = url.replace(':destinasi', '{{request()->segment(2)}}');
            callApi("GET", url, {}, function (req) {
                if (req.error) {
                    window.location.href = "{{route('destinasi')}}"
                } else {
                    $('#sisa-kursi').html("(Sisa: "+req.data.kursi_tersisa+")")
                    $('#sisa-bagasi').html("(Sisa: "+req.data.bagasi_tersisa+")")

                    $('#harga-kursi').html("Rp. "+toIdr(req.data.harga_kursi)+" / Item")
                    $('#harga-bagasi').html("Rp. "+toIdr(req.data.harga_bagasi)+" / Item")

                    $('#harga-kursi').attr("harga", req.data.harga_kursi)
                    $('#harga-bagasi').attr("harga", req.data.harga_bagasi)

                    harga_kursi = req.data.harga_kursi
                    harga_bagasi = req.data.harga_bagasi
                    destinasi_id = req.data.id

                    $('#hari_berangkat').html(req.data.hari_berangkat)
                    $('#destinasi').html(`${req.data.destinasi_awal} <i class="fa-solid fa-arrow-right"></i> ${req.data.destinasi_akhir}`)

                    $('.subtotal').html("Rp. "+toIdr(req.data.harga_kursi*1))
                }
            })
        }

        get_detail_destinasi();

        $("#kursi").on('change', function(){
            let total = (parseInt(harga_kursi) * $(this).val())
            let total_bagasi = (parseInt(harga_bagasi) * $("#bagasi").val())
            $('.subtotal').html("Rp. "+toIdr(total+total_bagasi))
        })

        $("#bagasi").on('change', function(){
            let total = (parseInt(harga_bagasi) * $(this).val())
            let total_kursi = (parseInt(harga_kursi) * $("#kursi").val())
            $('.subtotal').html("Rp. "+toIdr(total+total_kursi))
        })

        $('.btn-checkout').on('click', function(){
            $(this).attr('disabled', true)
            data = {
                id: destinasi_id,
                kursi: $("#kursi").val(),
                bagasi: $("#bagasi").val()
            }

            let url = "{{ route('api.destinasi.order') }}";

            callApi("POST", url, data, function (req) {
                pesan = req.message;
                if (req.error == true) {
                    Swal.fire(
                    'Gagal Melakukan Checkout!',
                    pesan,
                    'error'
                    )
                    $('.btn-checkout').attr('disabled', false)
                }else{
                    window.location.href = "{{route('pesanan')}}"
                }
            })
        })
    })
</script>
@endpush
