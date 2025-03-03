function callApi(t, n, a, e, f = function(){}) {
    let formData = new FormData();

    // Ambil token CSRF dari meta tag
    let csrfToken = $('meta[name="csrf-token"]').attr('content');

    // console.log(csrfToken);

    // Tambahkan token CSRF ke formData
    // if (csrfToken) {
    //     formData.append('_token', csrfToken);
    // }

    // Memproses data sebelum mengirimnya
    for (let key in a) {
        if (a.hasOwnProperty(key)) {
            if (Array.isArray(a[key])) {
                // Jika nilai adalah array
                for (let i = 0; i < a[key].length; i++) {
                    formData.append(key+"["+[i]+"]", a[key][i]);
                }
            } else {
                // Jika nilai bukan array
                formData.append(key, a[key]);
            }
        }
    }

    let setAuthorization = {};

    try {
        setAuthorization = { Authorization: getAuthorization() };
    } catch (error) {
    }

    if (csrfToken) {
        setAuthorization['X-CSRF-TOKEN'] = csrfToken;
    }

    let setContentType = false;

    if (t == 'PUT' || t == 'PATCH') {
        formData = new URLSearchParams();
        for (let key in a) {
            if (a.hasOwnProperty(key)) {
                if (Array.isArray(a[key])) {
                    // Jika nilai adalah array
                    for (let i = 0; i < a[key].length; i++) {
                        formData.append(key+"["+[i]+"]", a[key][i]);
                    }
                } else {
                    // Jika nilai bukan array
                    formData.append(key, a[key]);
                }
            }
        }
        formData = formData.toString();
        setContentType = 'application/x-www-form-urlencoded';
    }

    // console.log(formData);

    $.ajax({ url: n, type: t, headers: setAuthorization, data: formData, processData: false, contentType: setContentType }).done(function (n) {
        e(n);
    }).fail(function (a, b, c) { f(a, b, c) });
}

function getBase64(file, name) {
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function () {
        sessionStorage.setItem(name, reader.result)
    };
    reader.onerror = function (error) {
        console.log('Error: ', error);
    };
}
