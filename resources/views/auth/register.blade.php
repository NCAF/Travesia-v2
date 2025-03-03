<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Travesia - Register</title>
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
                      <label class="form-label">Tipe Akun</label>
                      <select name="role" class="form-control">
                          <option value="">Pilih Akun</option>
                          <option value="seller">seller</option>
                          <option value="user">user</option>
                      </select>
                    </div>
                  <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">No. Telepon</label>
                    <input type="number" name="telp" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-control">
                        <option value="">Pilih Gender</option>
                        <option value="Laki-Laki">Laki-Laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <Textarea class="form-control" name="alamat" required></Textarea>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Foto Profile</label>
                    <div id="file--upload-foto"></div>
                  </div>
                  <a href="javascript:void(0);" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2 btn-register">Sign Up</a>
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">Already have an Account?</p>
                    <a class="text-primary fw-bold ms-2" href="{{route('login')}}">Sign In</a>
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
    let UploadFile = new FileUpload('#file--upload-foto',{
        accept: [
            'png',
            'jpg',
            'jpeg'
        ],
        maxSize: 1,
        maxFile: 1
    });

    $(document).on('click', '.btn-register', function (e) {
        e.preventDefault()

        data = {
            role: $("select[name=role] option:selected").val(),
            nama: $("input[name=nama]").val(),
            email: $("input[name=email]").val(),
            telp: $("input[name=telp]").val(),
            gender: $("select[name=gender] option:selected").val(),
            alamat: $("textarea[name=alamat]").val(),
            password: $("input[name=password]").val(),
            password_confirmation: $("input[name=password_confirmation]").val(),
            foto: UploadFile.getFiles(),
        }

        callApi("POST", "{{ route('api.register') }}", data, function (req) {
            pesan = req.message;
            if (req.error == true) {
                Swal.fire(
                    'Gagal!',
                    pesan,
                    'error'
                )
            } else {
                Swal.fire({
                    title: 'Berhasil!',
                    text: pesan,
                    icon: 'success',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('login') }}";
                    }
                });
            }
        })
    })
  </script>
</body>

</html>
