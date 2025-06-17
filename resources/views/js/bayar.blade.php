@push("script")
<script type="text/javascript"
		src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
    $(document).ready(function() {
        var snap_token = ''
        function get_order() {
            let url = "{{ route('api.orders.show', ':id') }}";
            url = url.replace(':id', '{{request()->segment(2)}}');
            callApi("GET", url, {}, function (req) {
                if (req.error) {
                    window.location.href = "{{route('pesanan')}}"
                } else {
                    $('#harga-kursi').html("Rp. "+toIdr(req.data.harga_kursi)+" / Item")
                    $('#harga-bagasi').html("Rp. "+toIdr(req.data.harga_bagasi)+" / Item")

                    $('#kursi').val(req.data.jumlah_kursi)
                    $('#bagasi').val(req.data.jumlah_bagasi)
                    $('#kode_destinasi').val(req.data.kode_destinasi)

                    $('#hari_berangkat').html(req.data.hari_berangkat)
                    $('#destinasi').html(`${req.data.destinasi_awal} <i class="fa-solid fa-arrow-right"></i> ${req.data.destinasi_akhir}`)

                    $('.subtotal').html("Rp. "+toIdr(req.data.subtotal))
                    snap_token = req.data.token

                    $('.btn-bayar').attr('disabled', false)
                }
            })
        }

        get_order();

        $('.btn-bayar').on('click', function(){
            snap.pay(`${snap_token}`, {
                onSuccess: function(result){
                    data = {
                        id: '{{request()->segment(2)}}',
                        status: 'paid'
                    }
                    let url = "{{ route('api.orders.pay') }}";

                    callApi("POST", url, data, function (req) {
                        pesan = req.message;
                        if (req.error == true) {
                            Swal.fire(
                            'Gagal Melakukan Pembayaran!',
                            pesan,
                            'error'
                            )
                        }else{
                            window.location.href = "{{route('pesanan')}}"
                        }
                    })
                },
                onPending: function(result){

                },
                onError: function(result){
                    data = {
                        id: '{{request()->segment(2)}}',
                        status: 'cancelled'
                    }
                    let url = "{{ route('api.orders.pay') }}";

                    callApi("POST", url, data, function (req) {
                        pesan = req.message;
                        if (req.error == true) {
                            Swal.fire(
                            'Gagal Melakukan Pembayaran!',
                            pesan,
                            'error'
                            )
                        }else{
                            window.location.href = "{{route('pesanan')}}"
                        }
                    })
                }
            });
        })
    })
</script>
@endpush
