@push("script")
<script>
    $(document).ready(function() {
        function get_detail_destinasi() {
            let url = "{{ route('api.destinasi.show', ':destinasi') }}";
            url = url.replace(':destinasi', '{{request()->segment(2)}}');
            callApi("GET", url, {}, function (req) {
                if (req.error) {
                    $('.detail-destinasi-none').html(req.message)
                } else {
                    $('.detail-destinasi-none').addClass('d-none')
                    $('.detail-destinasi').removeClass('d-none')

                    $('#destinasi_awal').html(req.data.destinasi_awal)
                    $('#destinasi_akhir').html(req.data.destinasi_akhir)
                    $('#hari_berangkat').html(req.data.hari_berangkat)
                    $('#foto-destinasi').attr("src", "{{url('')}}"+req.data.foto)
                    $('#status-destinasi').html(req.data.status)
                    $('.info-destinasi').html(`
                        <h6>
                            <b>Kendaraan:</b> <br><b>${req.data.jenis_kendaraan}</b> (${req.data.no_plat})<br class="mb-4">
                            <b>Kursi Tersisa:</b> <br><b>${req.data.kursi_tersisa}</b> (Rp. ${toIdr(req.data.harga_kursi)})<br class="mb-4">
                            <b>Bagasi Tersisa:</b> <br><b>${req.data.bagasi_tersisa}</b> (Rp. ${toIdr(req.data.harga_bagasi)})<br class="mb-4">
                            <b>Deskripsi:</b> <br>${req.data.deskripsi}
                        </h6>
                    `)
                }
            })
        }

        get_detail_destinasi();
    })
</script>
@endpush
