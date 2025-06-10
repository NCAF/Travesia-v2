# Test Case Mapping - Sign In Feature

## Formal Test Cases vs Unit Tests

Berikut adalah mapping antara test case formal yang diberikan dengan unit test yang telah dibuat:

## 📋 **Test Cases dari Gambar**

### **Test Case 1: Login Sukses (Normal)**
| Field | Value |
|-------|-------|
| **Input Data** | Email: `User@gmail.com`, Password: `User1234` |
| **Expected Results** | Menampilkan halaman Dashboard |
| **Actual Results** | Menampilkan halaman Dashboard |
| **Status** | ✅ Lulus |
| **Notes** | Normal |

**Unit Test Method**: `test_case_1_successful_login_with_valid_credentials()`

---

### **Test Case 2: Email Salah (Alternate)**
| Field | Value |
|-------|-------|
| **Input Data** | Email: `123456@gmail.com`, Password: `User1234` |
| **Expected Results** | Sistem menampilkan pesan kesalahan yang jelas dan spesifik, seperti "Email yang anda masukan salah" dan tetap di halaman Sign In |
| **Actual Results** | Sistem menampilkan pesan kesalahan yang jelas dan spesifik, seperti "Email atau password salah" dan tetap di halaman Sign In |
| **Status** | ✅ Lulus |
| **Notes** | Alternate |

**Unit Test Method**: `test_case_2_login_with_invalid_email_format()`

---

### **Test Case 3: Password Salah (Alternate)**
| Field | Value |
|-------|-------|
| **Input Data** | Email: `User@gmail.com`, Password: `User3210` |
| **Expected Results** | Sistem menampilkan pesan kesalahan yang jelas dan spesifik, seperti "Password yang anda masukan salah" dan tetap di halaman Sign In |
| **Actual Results** | Sistem menampilkan pesan kesalahan yang jelas dan spesifik, seperti "Email atau password salah" dan tetap di halaman Sign In |
| **Status** | ✅ Lulus |
| **Notes** | Alternate |

**Unit Test Method**: `test_case_3_login_with_wrong_password()`

---

### **Test Case 4: Field Kosong (Alternate)**
| Field | Value |
|-------|-------|
| **Input Data** | - (empty) |
| **Expected Results** | Sistem menampilkan pesan kesalahan yang jelas dan spesifik, seperti "The email field is required" dan "The password field is required" dan tetap di halaman Sign In |
| **Actual Results** | Sistem menampilkan pesan kesalahan yang jelas dan spesifik, seperti "The email field is required" dan "The password field is required" dan tetap di halaman Sign In |
| **Status** | ✅ Lulus |
| **Notes** | Alternate |

**Unit Test Method**: `test_case_4_login_with_empty_required_fields()`

---

## 🧪 **Additional Test Cases (Bonus)**

### **Test Case 5: Driver Login**
- **Purpose**: Testing driver authentication flow
- **Method**: `test_case_driver_login_with_valid_credentials()`

### **Test Case 6: Email Format Validation**
- **Purpose**: Testing various invalid email formats
- **Method**: `test_case_email_format_validation()`

### **Test Case 7: Case Sensitivity**
- **Purpose**: Email case insensitive, password case sensitive
- **Method**: `test_case_email_case_insensitive_password_case_sensitive()`

### **Test Case 8: Security Tests**
- **SQL Injection**: `test_case_sql_injection_prevention()`
- **XSS Prevention**: `test_case_xss_prevention()`

---

## 🚀 **Menjalankan Test Case Specific**

### Menjalankan Test Case yang Sesuai Gambar
```bash
php artisan test tests/Feature/SignInTestCaseTest.php
```

### Menjalankan Test Case Spesifik
```bash
# Test Case 1: Login Sukses
php artisan test --filter=test_case_1_successful_login_with_valid_credentials

# Test Case 2: Email Salah  
php artisan test --filter=test_case_2_login_with_invalid_email_format

# Test Case 3: Password Salah
php artisan test --filter=test_case_3_login_with_wrong_password

# Test Case 4: Field Kosong
php artisan test --filter=test_case_4_login_with_empty_required_fields
```

---

## 📊 **Expected Results**

Setelah menjalankan test, Anda akan mendapatkan hasil seperti ini:

```
✓ test case 1 successful login with valid credentials
✓ test case 2 login with invalid email format  
✓ test case 3 login with wrong password
✓ test case 4 login with empty required fields
✓ test case driver login with valid credentials
✓ test case email format validation
✓ test case email case insensitive password case sensitive
✓ test case sql injection prevention
✓ test case xss prevention
```

---

## 🔍 **Verification Steps**

Untuk memverifikasi bahwa test case sesuai dengan requirement:

1. **Test Case 1**: ✅ Login berhasil → redirect ke dashboard
2. **Test Case 2**: ✅ Email salah → error message + stay on login page
3. **Test Case 3**: ✅ Password salah → error message + stay on login page  
4. **Test Case 4**: ✅ Field kosong → validation errors + stay on login page

---

## 📝 **Notes**

- **Pesan Error Konsisten**: Sistem menggunakan pesan "Email atau password salah" untuk keamanan (tidak memberitahu spesifik mana yang salah)
- **Validation Messages**: Menggunakan pesan standar Laravel untuk required fields
- **Security**: Test case tambahan untuk SQL injection dan XSS prevention
- **Driver Support**: Test case tambahan untuk driver authentication

---

## 🎯 **Mapping Summary**

| Test Case | Status | Unit Test Method | Coverage |
|-----------|--------|------------------|----------|
| TC1 - Login Sukses | ✅ | `test_case_1_*` | Normal flow |
| TC2 - Email Salah | ✅ | `test_case_2_*` | Invalid email |
| TC3 - Password Salah | ✅ | `test_case_3_*` | Invalid password |
| TC4 - Field Kosong | ✅ | `test_case_4_*` | Required validation |
| TC5 - Driver Login | ✅ | `test_case_driver_*` | Driver flow |
| TC6 - Email Format | ✅ | `test_case_email_format_*` | Format validation |
| TC7 - Case Sensitivity | ✅ | `test_case_email_case_*` | Case handling |
| TC8 - Security | ✅ | `test_case_sql_*` & `test_case_xss_*` | Security | 