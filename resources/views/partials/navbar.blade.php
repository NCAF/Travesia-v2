<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            @if (Auth::check())
                <a class="navbar-brand" href="{{ route('user.dashboard') }}">
                    <img src="{{ asset('img/travesia.png') }}" alt="logo travesia" width="118" height="24">
                </a>
            @else
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <img src="{{ asset('img/travesia.png') }}" alt="logo travesia" width="118" height="24">
                </a>
            @endif
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        @if (Auth::check())
                            <a class="nav-link" href="{{ route('user.destination-list') }}">Destination</a>
                        @else
                            <a class="nav-link" href="{{ route('user.destination-list-not-login') }}">Destination</a>
                        @endif
                    </li>
                    @if (Auth::check())
                    <li class="nav-item">
                        <a class="nav-link" href="#">Chat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.order-lists') }}">My Ticket</a>
                    </li>
                    @endif
                </ul>
                {{-- ini untuk guest --}}
                @if (!Auth::check())
                    <a href="{{ route('login') }}" class="custom-btn-outline btn fw-bold mx-2 d-none d-lg-inline">Sign In</a>
                    <a href="{{ route('register') }}" class="custom-btn btn fw-bold mx-2 d-none d-lg-inline">Sign Up</a>
                @endif

                <!-- Teks di Mobile & Tablet -->
                {{-- <ul class="navbar-nav d-lg-none">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Sign In</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Sign Up</a>
                    </li>
                </ul> --}}

                <!-- @if (Auth::check())
                    @if (Auth::user()->role == 'user')
                        <a href="{{ route('user.dashboard') }}" class="btn btn-primary">Dashboard</a>
                    @elseif (Auth::user()->role == 'driver')
                        <a href="{{ route('driver.dashboard') }}" class="btn btn-primary">Dashboard</a>
                    @endif
                @endif -->

                {{-- ini untuk user/driver --}}
                <!-- User Profile Desktop -->
                 @if (Auth::check())
                    <div class="d-none d-lg-flex align-items-center dropdown">
                        <div class="text-end me-2">
                            <span class="fw-bold d-block">{{ Str::limit(Auth::user()->nama, 8) }}</span>
                            <span class="text-muted small">{{ Auth::user()->role }}</span>
                        </div>

                        <!-- Dropdown Toggle -->
                        <img src="{{ asset('icons/icon-img.svg') }}" alt="Profile Image"
                            class="rounded-circle dropdown-toggle" width="32" height="32" id="profileDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">

                        <!-- Dropdown Menu -->
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                        </ul>
                    </div>
                @endif

                <!-- Teks di Mobile & Tablet -->
                <ul class="navbar-nav d-lg-none">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
</div>
