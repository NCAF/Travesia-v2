<!-- /*
* Template Name: Tour
* Template Author: Untree.co
* Tempalte URI: https://untree.co/
* License: https://creativecommons.org/licenses/by/3.0/
*/ -->
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="you-ink">
	<link rel="shortcut icon" href="{{ url('assets/images/icon.png') }}">

	<meta name="description" content="Travesia. Menemani perjalaan anda dengan penuh kenyamanan." />
	<meta name="keywords" content="travel, indonesia, keliling, perjalanan, marketplace" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Source+Serif+Pro:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="{{url('assets/css/bootstrap.min.css')}}">
	{{-- <link rel="stylesheet" href="{{url('assets/css/fontawesome6.all.min.css')}}"> --}}
	<link rel="stylesheet" href="{{url('assets/css/owl.carousel.min.css')}}">
	<link rel="stylesheet" href="{{url('assets/css/owl.theme.default.min.css')}}">
	<link rel="stylesheet" href="{{url('assets/css/jquery.fancybox.min.css')}}">
	<link rel="stylesheet" href="{{url('assets/fonts/icomoon/style.css')}}">
	<link rel="stylesheet" href="{{url('assets/fonts/flaticon/font/flaticon.css')}}">
	<link rel="stylesheet" href="{{url('assets/css/daterangepicker.css')}}">
	<link rel="stylesheet" href="{{url('assets/css/aos.css')}}">
	<link rel="stylesheet" href="{{url('assets/css/style.css')}}">
    <link rel="stylesheet" href="{{url('assets/css/custom.css')}}" />

	<title>Travesia @yield('title')</title>
</head>

<body>


	<div class="site-mobile-menu site-navbar-target">
		<div class="site-mobile-menu-header">
			<div class="site-mobile-menu-close">
				<span class="icofont-close js-menu-toggle"></span>
			</div>
		</div>
		<div class="site-mobile-menu-body"></div>
	</div>

	<nav class="site-nav">
		<div class="container">
			<div class="site-navigation">
				<a href="{{url('')}}" class="logo m-0">
                    <img src="{{ url('assets/images/travesia.png') }}" width="150px">
                </a>

				<ul class="js-clone-nav d-none d-lg-inline-block text-left site-menu float-right">
					<li class="{{ (request()->segment(1) == '') ? 'active' : '' }}"><a href="{{route('home')}}">Home</a></li>
					<li class="{{ (request()->segment(1) == 'destinasi') ? 'active' : '' }}"><a href="{{route('destinasi')}}">Destinasi</a></li>
                    @if (!empty($user))
                        @if ($user->role == 'user')
                            <li class="{{ (request()->segment(1) == 'pesanan') ? 'active' : '' }}"><a href="{{route('pesanan')}}">Pesanan</a></li>
                            <li class="{{ (request()->segment(1) == 'chat') ? 'active' : '' }}"><a href="{{route('chat')}}">Chat</a></li>
                            <li></li>
                            <li class="has-children">
                                <a href="javascript:void(0);">
                                    <img src="{{url($user->foto)}}" alt="Image" width="30px" class="img-fluid rounded">&nbsp;&nbsp;&nbsp;{{$user->nama}}
                                </a>
                                <ul class="dropdown">
                                    <li><a href="javascript:void(0);"><i class="fa-solid fa-user"></i>&nbsp;&nbsp;&nbsp;Profile</a></li>
                                    <hr>
                                    <li><a href="javascript:void(0);" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i>&nbsp;&nbsp;&nbsp;Logout</a></li>
                                </ul>
                            </li>
                        @else
                            <li><a href="{{route('seller.main')}}">Dashboard Seller</a></li>
                        @endif
                    @else
                        <li></li>
                        <li>
                            <button class="btn btn-sm btn-outline-light btn-login">Log In</button>
                        </li>
                        <li>
                            <button class="btn btn-sm btn-primary btn-register">Register</button>
                        </li>
                    @endif
				</ul>

				<a href="#" class="burger ml-auto float-right site-menu-toggle js-menu-toggle d-inline-block d-lg-none light" data-toggle="collapse" data-target="#main-navbar">
					<span></span>
				</a>

			</div>
		</div>
	</nav>


    {{-- ISI KONTEN --}}
    @yield('content')


	<div class="site-footer">
		<div class="inner first">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<div class="widget">
							<h3 class="heading">About Travesia</h3>
							<p>Travesia adalah platform jual beli tiket travel yang memudahkan pengguna untuk menemukan, memesan, dan mengelola perjalanan dengan berbagai pilihan destinasi dan layanan transportasi.</p>
						</div>
						<div class="widget">
							<ul class="list-unstyled social">
								<li><a href="#"><span class="icon-twitter"></span></a></li>
								<li><a href="#"><span class="icon-instagram"></span></a></li>
								<li><a href="#"><span class="icon-facebook"></span></a></li>
								<li><a href="#"><span class="icon-linkedin"></span></a></li>
								<li><a href="#"><span class="icon-dribbble"></span></a></li>
								<li><a href="#"><span class="icon-pinterest"></span></a></li>
								<li><a href="#"><span class="icon-apple"></span></a></li>
								<li><a href="#"><span class="icon-google"></span></a></li>
							</ul>
						</div>
					</div>
					<div class="col-md-6">
						<div class="widget">
							<h3 class="heading">Contact</h3>
							<ul class="list-unstyled quick-info links">
								<li class="email"><a href="#">mail@example.com</a></li>
								<li class="phone"><a href="#">+1 222 212 3819</a></li>
								<li class="address"><a href="#">43 Raymouth Rd. Baltemoer, London 3910</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>



		<div class="inner dark">
			<div class="container">
				<div class="row text-center">
					<div class="col-md-8 mb-3 mb-md-0 mx-auto">
						<p>Copyright &copy;<script>document.write(new Date().getFullYear());</script>. All Rights Reserved. &mdash; Designed with love by <a href="https://untree.co" class="link-highlight">Untree.co</a> <!-- License information: https://untree.co/license/ -->
						</p>
					</div>

				</div>
			</div>
		</div>
	</div>

	<div id="overlayer"></div>
	<div class="loader">
		<div class="spinner-border" role="status">
			<span class="sr-only">Loading...</span>
		</div>
	</div>

	<script src="{{url('assets/js/jquery-3.6.1.min.js')}}"></script>
	<script src="{{url('assets/js/popper.min.js')}}"></script>
	<script src="{{url('assets/js/bootstrap.min.js')}}"></script>
	<script src="{{url('assets/js/owl.carousel.min.js')}}"></script>
	<script src="{{url('assets/js/jquery.animateNumber.min.js')}}"></script>
	<script src="{{url('assets/js/jquery.waypoints.min.js')}}"></script>
	<script src="{{url('assets/js/jquery.fancybox.min.js')}}"></script>
	<script src="{{url('assets/js/aos.js')}}"></script>
	<script src="{{url('assets/js/moment.min.js')}}"></script>
	<script src="{{url('assets/js/daterangepicker.js')}}"></script>
    <script src="{{ url('assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{url('assets/js/main.js')}}"></script>
    <script src="{{url('assets/js/jquery-cookie.min.js')}}"></script>
    <script src="{{url('assets/js/api.js')}}"></script>
	<script>
        @if (!empty($user))
            @if ($user->role == 'user')
                $(document).on('click', '.btn-logout', function(e) {
                    e.preventDefault();

                    Swal.fire({
                    title: 'Logout?',
                    text: `Anda ingin melakukan logout!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, logout!'
                    }).then((result) => {
                    if (result.isConfirmed) {

                        callApi("POST", "{{ route('api.logout') }}", {}, function (req) {
                            pesan = req.message;
                            if (req.error == true) {
                                Swal.fire(
                                    'Gagal melakukan logout!',
                                    pesan,
                                    'error'
                                )
                            } else {
                                cookie.remove('travesia_token')
                                window.location.href = "{{ route('home') }}"
                            }
                        })

                    }
                    })
                });
            @endif
        @endif
        $(document).on('click', '.btn-login', function(e) {
            e.preventDefault();
            window.location.href = "{{ route('login') }}"
        });

        $(document).on('click', '.btn-register', function(e) {
            e.preventDefault();
            window.location.href = "{{ route('register') }}"
        });
	</script>

	<script src="{{url('assets/js/custom.js')}}"></script>

    @stack('script')

</body>

</html>
