@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        confirmButtonText: 'OK',
        confirmButtonColor: '#d33',
        @if(session('payment_failed'))
        footer: '<a href="/user/order-lists">Lihat daftar pesanan</a>'
        @endif
    });
</script>
@endif

@if(session('warning'))
<script>
    Swal.fire({
        icon: 'warning',
        title: 'Perhatian!',
        text: '{{ session('warning') }}',
        confirmButtonText: 'OK',
        confirmButtonColor: '#ffc107',
        @if(session('show_retry_button') && session('order_id'))
        showCancelButton: true,
        cancelButtonText: 'Coba Lagi',
        cancelButtonColor: '#28a745',
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.cancel) {
            // Redirect to retry payment
            window.location.href = '/bayar/{{ session('order_id') }}';
        }
    });
    @else
    });
    @endif
</script>
@endif

@if(session('info'))
<script>
    Swal.fire({
        icon: 'info',
        title: 'Informasi',
        text: '{{ session('info') }}',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6'
    });
</script>
@endif 