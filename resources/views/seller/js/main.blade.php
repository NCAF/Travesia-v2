@push('script')
<script src="{{ url('assets/admin-template/js/dashboard.js')}}"></script>
<script>
(function () {
    callApi("GET", "{{route('api.dashboard.index')}}", {}, function (req) {
        $('#destinasi').html(req.data.destinasi)
        $('#pesanan').html(req.data.pesanan)
        $('#total_pendapatan').html("Rp. "+req.data.total_pendapatan)
        $('#pendapatan_bulan_ini').html("Rp. "+req.data.pendapatan_bulan_ini)
    })
})();
</script>
@endpush
