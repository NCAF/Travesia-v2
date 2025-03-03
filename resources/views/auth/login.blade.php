<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Travesia - Login</title>
  <link rel="shortcut icon" href="{{ url('assets/images/icon.png') }}">
  <meta name="author" content="you-ink">
  <meta name="description" content="Travesia. Menemani perjalaan anda dengan penuh kenyamanan." />
  <meta name="keywords" content="travel, indonesia, keliling, perjalanan, marketplace" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{url('assets/admin-template/css/styles.min.css')}}" />
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="{{route('home')}}" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="{{ url('assets/images/travesia.png') }}" width="180" alt="">
                </a>
                <p class="text-center">Make your travel more eazier</p>
                <form>
                  <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                  </div>
                  <div class="mb-4">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="exampleInputPassword1">
                  </div>
                  <a href="javascript:void(0);" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2 btn-login">Sign In</a>
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">New to Travesia?</p>
                    <a class="text-primary fw-bold ms-2" href="{{route('register')}}">Create an account</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="{{url('assets/admin-template/libs/jquery/dist/jquery.min.js')}}"></script>
  <script src="{{url('assets/admin-template/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{ url('assets/js/sweetalert2.all.min.js') }}"></script>
  <script src="{{ url('assets/js/main.js') }}"></script>
  <script src="{{ url('assets/js/file-upload.js') }}"></script>
  <script src="{{url('assets/js/jquery-cookie.min.js')}}"></script>
  <script src="{{url('assets/js/api.js')}}"></script>
  <script>
    $(document).on('click', '.btn-login', function (e) {
        e.preventDefault()

        data = {
            email: $("input[name=email]").val(),
            password: $("input[name=password]").val()
        }

        callApi("POST", "{{ route('api.login') }}", data, function (req) {
            pesan = req.message;
            if (req.error == true) {
                Swal.fire(
                    'Gagal!',
                    pesan,
                    'error'
                )
            } else {
                Swal.fire(
                    'Berhasil!',
                    pesan,
                    'success'
                )
                cookie.set('travesia_token', req.data.token);
                window.location.href = "{{ route('login') }}"
            }
        })
    })
  </script>
</body>

</html>
