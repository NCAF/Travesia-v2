<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Travesia Seller - @yield('title')</title>
  <link rel="shortcut icon" href="{{ url('assets/images/icon.png') }}">
  <meta name="author" content="you-ink">
  <meta name="description" content="Travesia. Menemani perjalaan anda dengan penuh kenyamanan." />
  <meta name="keywords" content="travel, indonesia, keliling, perjalanan, marketplace" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{url('assets/admin-template/css/styles.min.css')}}" />
  <link rel="stylesheet" href="{{url('assets/admin-template/css/custom.css')}}" />
  <link rel="stylesheet" href="{{url('assets/css/dataTables.bootstrap5.min.css')}}" />
</head>
<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">

        <!-- Sidebar Start -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="" class="text-nowrap logo-img">
                        <img src="{{ url('assets/images/travesia.png') }}" width="180" alt="" />
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8"></i>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Home</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ (request()->segment(2) == '' || request()->segment(2) == 'index') ? 'active' : '' }}" href="{{ route('seller.main') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-layout-dashboard"></i>
                            </span>
                            <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">DATA</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->segment(2) == 'destinasi' ? 'active' : '' }}" href="{{ route('seller.destinasi') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-location"></i>
                            </span>
                            <span class="hide-menu">Destinasi</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->segment(2) == 'pesanan' ? 'active' : '' }}" href="{{ route('seller.pesanan') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-clipboard-list"></i>
                            </span>
                            <span class="hide-menu">Pesanan</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->segment(2) == 'chat' ? 'active' : '' }}" href="{{ route('seller.chat') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-messages"></i>
                            </span>
                            <span class="hide-menu">Chat</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!--  Sidebar End -->

        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                <ul class="navbar-nav">
                    <li class="nav-item d-block d-xl-none">
                    <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                        <i class="ti ti-menu-2"></i>
                    </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-icon-hover" href="{{route('seller.chat')}}">
                            <i class="ti ti-messages"></i>
                            <div class="notification bg-primary rounded-circle"></div>
                        </a>
                    </li>
                </ul>
                <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                    <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                        <li class="nav-item dropdown">
                            <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                            aria-expanded="false">
                                <img src="{{ url($user->foto) }}" alt="" width="35" height="35" class="rounded-circle">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                            <div class="message-body">
                                <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                    Halo, {{ $user->nama }}
                                </a>
                                <hr>
                                <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                    <i class="ti ti-user fs-6"></i>
                                    <p class="mb-0 fs-3">My Profile</p>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-outline-primary mx-3 mt-2 d-block btn-logout">Logout</a>
                            </div>
                            </div>
                        </li>
                    </ul>
                </div>
                </nav>
            </header>
            <!--  Header End -->
            <div class="container-fluid">

                @yield('content')

                <div class="py-6 px-6 text-center">
                    <p class="mb-0 fs-4">&copy; Copyright {{ date('Y') }}. All right reserved.</p>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ url('assets/admin-template/libs/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{ url('assets/admin-template/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ url('assets/admin-template/js/sidebarmenu.js')}}"></script>
    <script src="{{ url('assets/admin-template/js/app.min.js')}}"></script>
    <script src="{{ url('assets/admin-template/libs/apexcharts/dist/apexcharts.min.js')}}"></script>
    <script src="{{ url('assets/admin-template/libs/simplebar/dist/simplebar.js')}}"></script>
    <script src="{{ url('assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ url('assets/js/main.js') }}"></script>
    <script src="{{ url('assets/js/file-upload.js') }}"></script>
    <script src="{{ url('assets/js/jquery-cookie.min.js')}}"></script>
    <script src="{{ url('assets/js/api.js')}}"></script>
    <script src="{{ url('assets/js/dataTables.min.js')}}"></script>
    <script src="{{ url('assets/js/dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{ url('assets/js/moment.min.js')}}"></script>

    @stack('script')
    <script>
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
                        window.location.href = "{{ route('login') }}"
                    }
                })

            }
            })
        });
    </script>
</body>
</html>
