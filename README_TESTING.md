# Unit Testing untuk Fitur Sign In/Login - Laravel Travesia

## Overview

Dokumen ini menjelaskan unit testing yang telah dibuat untuk fitur Sign In/Login pada aplikasi Laravel Travesia. Testing ini mencakup berbagai skenario untuk memastikan fungsionalitas authentication bekerja dengan benar.

## Test Files Yang Dibuat

### 1. `tests/Unit/AuthTest.php`
Test utama untuk fungsionalitas authentication yang mencakup:

- ✅ **User Login Success**: Test login berhasil dengan kredensial valid
- ✅ **Driver Login Success**: Test login driver berhasil dengan kredensial valid  
- ✅ **Invalid Email Test**: Test login gagal dengan email yang tidak terdaftar
- ✅ **Invalid Password Test**: Test login gagal dengan password yang salah
- ✅ **Validation Empty Fields**: Test validasi untuk field kosong
- ✅ **Invalid Email Format**: Test validasi untuk format email yang salah
- ✅ **Role-based Redirect**: Test redirect berdasarkan role user
- ✅ **Session Regeneration**: Test regenerasi session saat login
- ✅ **Driver Session Data**: Test penyimpanan data session untuk driver
- ✅ **Independent Login**: Test bahwa login user dan driver independent
- ✅ **Case Sensitivity**: Test case sensitivity pada password

### 2. `tests/Feature/LoginFeatureTest.php`
Test end-to-end untuk flow login yang mencakup:

- ✅ **Login Page Access**: Test akses halaman login
- ✅ **User Login Flow**: Test flow login user dari form sampai redirect
- ✅ **Driver Login Flow**: Test flow login driver lengkap
- ✅ **Invalid Credentials Error**: Test tampilan error untuk kredensial salah
- ✅ **Form Validation**: Test validasi form login
- ✅ **Authenticated Redirect**: Test redirect untuk user yang sudah login
- ✅ **Role Determination**: Test penentuan redirect berdasarkan role
- ✅ **Intended URL**: Test preservasi URL tujuan setelah login
- ✅ **Multiple Failed Attempts**: Test multiple percobaan login gagal
- ✅ **Logout Functionality**: Test fungsionalitas logout
- ✅ **Driver Logout**: Test logout khusus untuk driver
- ✅ **Rate Limiting**: Test untuk rate limiting (jika diimplementasikan)

### 3. `tests/Unit/AuthControllerTest.php`
Test khusus untuk controller methods:

- ✅ **View Returns**: Test return view untuk login, register, dan register driver
- ✅ **Login Processing**: Test method loginPost dengan berbagai skenario
- ✅ **Driver Authentication**: Test autentikasi driver melalui controller
- ✅ **Session Management**: Test pengelolaan session dalam controller
- ✅ **User Registration**: Test pembuatan user baru
- ✅ **Password Hashing**: Test hashing password saat registrasi
- ✅ **Login Precedence**: Test prioritas login user over driver

### 4. `tests/Unit/LoginValidationTest.php`
Test spesifik untuk validasi input login:

- ✅ **Required Fields**: Test field email dan password wajib diisi
- ✅ **Email Format**: Test validasi format email
- ✅ **Data Types**: Test tipe data string untuk input
- ✅ **Edge Cases**: Test berbagai edge cases seperti string kosong, null, dsb
- ✅ **Special Characters**: Test karakter khusus dalam password
- ✅ **Email Variations**: Test variasi case dalam email
- ✅ **Length Validation**: Test validasi panjang input

## Struktur Authentication

Aplikasi ini memiliki 2 jenis authentication:

### 1. User Authentication
- Model: `User`
- Table: `users`
- Role: `'user'` (enum)
- Redirect: `user.dashboard`

### 2. Driver Authentication  
- Model: `Driver`
- Table: `driver`
- Session-based authentication
- Redirect: `driver.destination-list`

## Konfigurasi Testing

### Database Testing
```xml
<!-- phpunit.xml -->
<env name="DB_CONNECTION" value="mysql"/>
<env name="DB_DATABASE" value="db_travesia"/>
```

### Migration untuk Testing
```bash
php artisan migrate:fresh --env=testing
```

## Menjalankan Tests

### Semua Test Authentication
```bash
php artisan test --filter="Auth|Login"
```

### Test Spesifik
```bash
# Unit Tests
php artisan test tests/Unit/AuthTest.php
php artisan test tests/Unit/AuthControllerTest.php
php artisan test tests/Unit/LoginValidationTest.php

# Feature Tests
php artisan test tests/Feature/LoginFeatureTest.php
```

## Hasil Testing

### Summary
- **Total Tests**: 52 tests
- **Status**: ✅ 52 passed, 1 risky
- **Assertions**: 182 assertions
- **Duration**: ~6.66s

### Coverage Areas
1. **Authentication Logic** ✅
2. **Session Management** ✅
3. **Input Validation** ✅
4. **Security (Password Hashing)** ✅
5. **Role-based Access** ✅
6. **Error Handling** ✅
7. **Edge Cases** ✅

## Fitur Yang Ditest

### Login Functionality
- [x] User login dengan email/password
- [x] Driver login dengan email/password  
- [x] Validasi input form
- [x] Error handling untuk kredensial salah
- [x] Session management
- [x] Redirect berdasarkan role
- [x] Logout functionality

### Security Features
- [x] Password hashing
- [x] Session regeneration
- [x] CSRF protection (melalui Laravel middleware)
- [x] Input sanitization
- [x] SQL injection prevention (melalui Eloquent)

### Edge Cases
- [x] Empty input fields
- [x] Invalid email format
- [x] Case sensitivity
- [x] Special characters
- [x] Multiple failed attempts
- [x] Same email different tables (User vs Driver)

## Best Practices Implemented

1. **AAA Pattern**: Arrange, Act, Assert dalam setiap test
2. **Database Transactions**: Menggunakan `RefreshDatabase` trait
3. **Isolation**: Setiap test berjalan independen
4. **Descriptive Naming**: Nama method yang jelas dan deskriptif
5. **Edge Case Coverage**: Testing berbagai skenario edge case
6. **Mock Data**: Menggunakan factory pattern untuk test data

## Notes

- Test menggunakan database `db_travesia` untuk testing
- Beberapa test di-skip untuk skenario yang tidak applicable (seperti driver role dalam User table)
- Rate limiting test adalah placeholder karena belum diimplementasikan
- Password hashing menggunakan bcrypt dengan rounds=4 untuk testing (lebih cepat)

## Troubleshooting

### Common Issues
1. **Database Connection**: Pastikan Laragon/MySQL running
2. **Migration Issues**: Run `php artisan migrate:fresh --env=testing`
3. **Role Enum**: User table hanya support role 'user', bukan 'driver'
4. **Session Issues**: Pastikan `SESSION_DRIVER=array` dalam phpunit.xml 