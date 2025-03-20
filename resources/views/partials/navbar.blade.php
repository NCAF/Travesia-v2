<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('img/travesia.png') }}" alt="logo travesia" width="118" height="24">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Destination</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Chat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">My Ticket</a>
                    </li>
                </ul>
                {{-- ini untuk guest --}}
                <!-- Tombol di Desktop -->
                {{-- <button class="custom-btn-outline btn fw-bold mx-2 d-none d-lg-inline">Sign In</button>
                <button class="custom-btn btn fw-bold mx-2 d-none d-lg-inline">Sign Up</button> --}}

                <!-- Teks di Mobile & Tablet -->
                {{-- <ul class="navbar-nav d-lg-none">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sign In</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sign Up</a>
                    </li>
                </ul> --}}

                {{-- ini untuk user/driver --}}
                <!-- User Profile Desktop -->
                <div class="d-none d-lg-flex align-items-center dropdown">
                    <div class="text-end me-2">
                        <span class="fw-bold d-block">Muhammad Adib F.</span>
                        <span class="text-muted small">User</span>
                    </div>

                    <!-- Dropdown Toggle -->
                    <img src="{{ asset('icons/icon-img.svg') }}" alt="Profile Image"
                        class="rounded-circle dropdown-toggle" width="32" height="32" id="profileDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">

                    <!-- Dropdown Menu -->
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="#">Logout</a></li>
                    </ul>
                </div>


                <!-- Teks di Mobile & Tablet -->
                <ul class="navbar-nav d-lg-none">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Logout</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
</div>
