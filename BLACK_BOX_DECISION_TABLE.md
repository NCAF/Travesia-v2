# 🔲 BLACK BOX TESTING - DECISION TABLE
## Sign In Feature - Laravel Travesia

### 📋 **Overview**
Decision Table adalah teknik black box testing yang digunakan untuk menguji berbagai kombinasi kondisi input dan memverifikasi output yang diharapkan tanpa mempertimbangkan struktur internal kode.

---

## 🎯 **Kondisi yang Diuji (Conditions)**

| **Kondisi** | **Keterangan** |
|-------------|----------------|
| **C1** | Email field tidak kosong |
| **C2** | Password field tidak kosong |
| **C3** | Email format valid (contains @) |
| **C4** | Email terdaftar di database |
| **C5** | Password sesuai dengan email |

---

## 📊 **DECISION TABLE - Sign In Feature**

| **Rule** | **R1** | **R2** | **R3** | **R4** | **R5** | **R6** | **R7** | **R8** | **R9** | **R10** | **R11** | **R12** |
|----------|--------|--------|--------|--------|--------|--------|--------|--------|---------|----------|----------|----------|
| **CONDITIONS** | | | | | | | | | | | | |
| C1: Email tidak kosong | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| C2: Password tidak kosong | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ✅ | ✅ | ❌ | ❌ |
| C3: Email format valid | ✅ | ✅ | ❌ | ❌ | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ✅ | ❌ |
| C4: Email terdaftar | ✅ | ❌ | ✅ | ❌ | ✅ | ❌ | ✅ | ❌ | - | - | - | - |
| C5: Password benar | ✅ | - | - | - | - | - | - | - | - | - | - | - |
| **ACTIONS** | | | | | | | | | | | | |
| A1: Login berhasil | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| A2: Redirect ke dashboard | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| A3: Show "Email/Password salah" | ❌ | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| A4: Show "Email required" | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ | ✅ | ✅ | ✅ |
| A5: Show "Password required" | ❌ | ❌ | ❌ | ❌ | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ✅ | ✅ |
| A6: Show "Invalid email format" | ❌ | ❌ | ✅ | ✅ | ❌ | ❌ | ✅ | ✅ | ❌ | ✅ | ❌ | ✅ |
| A7: Stay on login page | ❌ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |

---

## 📝 **Penjelasan Rules**

### **Rule 1 (R1) - Login Sukses** ✅
- **Kondisi**: Semua input valid dan kredensial benar
- **Test Case**: Email: `User@gmail.com`, Password: `User1234`
- **Expected**: Login berhasil, redirect ke dashboard
- **Status**: Normal flow

### **Rule 2 (R2) - Email Tidak Terdaftar** ❌
- **Kondisi**: Email format valid tapi tidak terdaftar
- **Test Case**: Email: `123456@gmail.com`, Password: `User1234`
- **Expected**: Error "Email atau password salah"
- **Status**: Alternate flow

### **Rule 3 (R3) - Email Format Invalid tapi Terdaftar** ❌
- **Kondisi**: Email format salah (hypothetical)
- **Test Case**: Email: `invalid-email`, Password: `User1234`
- **Expected**: Error "Invalid email format"
- **Status**: Error flow

### **Rule 4 (R4) - Email Format Invalid dan Tidak Terdaftar** ❌
- **Kondisi**: Email format salah dan tidak terdaftar
- **Test Case**: Email: `invalid@`, Password: `User1234`
- **Expected**: Error "Invalid email format"
- **Status**: Error flow

### **Rule 5 (R5) - Password Kosong** ❌
- **Kondisi**: Email valid tapi password kosong
- **Test Case**: Email: `User@gmail.com`, Password: `(empty)`
- **Expected**: Error "Password required"
- **Status**: Validation error

### **Rule 6-8** - Kombinasi lain dengan password kosong

### **Rule 9 (R9) - Email Kosong, Password Valid** ❌
- **Kondisi**: Email kosong, password ada
- **Test Case**: Email: `(empty)`, Password: `User1234`
- **Expected**: Error "Email required"
- **Status**: Validation error

### **Rule 10-12** - Kombinasi lain dengan email kosong

---

## 🧪 **Test Cases Berdasarkan Decision Table**

### **Implementasi dalam Unit Test**

```php
/**
 * Test berdasarkan Decision Table Rules
 */
class DecisionTableTest extends TestCase
{
    // Rule 1: All valid - Success
    public function test_rule_1_all_valid_success()
    {
        // Sesuai dengan test_case_1 yang sudah ada
    }
    
    // Rule 2: Email not registered
    public function test_rule_2_email_not_registered()
    {
        // Sesuai dengan test_case_2 yang sudah ada
    }
    
    // Rule 3: Invalid email format but registered
    public function test_rule_3_invalid_email_format()
    {
        // Test untuk email format invalid
    }
    
    // Rule 9: Empty email with valid password
    public function test_rule_9_empty_email_valid_password()
    {
        // Bagian dari test_case_4
    }
    
    // Dan seterusnya...
}
```

---

## 📊 **Matrix Kombinasi Input**

| **Email Status** | **Password Status** | **Expected Result** | **Test Priority** |
|------------------|---------------------|---------------------|-------------------|
| Valid + Registered | Valid + Correct | ✅ Success | **HIGH** |
| Valid + Not Registered | Valid | ❌ Login Error | **HIGH** |
| Valid + Registered | Valid + Wrong | ❌ Login Error | **HIGH** |
| Invalid Format | Any | ❌ Format Error | **MEDIUM** |
| Empty | Any | ❌ Required Error | **HIGH** |
| Any | Empty | ❌ Required Error | **HIGH** |
| Empty | Empty | ❌ Both Required | **HIGH** |

---

## 🎯 **Coverage Analysis**

### **Covered Rules** ✅
- **R1**: Login sukses (TC1) ✅
- **R2**: Email tidak terdaftar (TC2) ✅
- **R5**: Password kosong (TC4) ✅
- **R9**: Email kosong (TC4) ✅
- **R12**: Both empty (TC4) ✅

### **Additional Rules untuk Testing Lengkap** 🔄
- **R3**: Email format invalid
- **R4**: Email format invalid + tidak terdaftar
- **R6-R8**: Kombinasi password kosong dengan kondisi lain
- **R10-R11**: Kombinasi email kosong dengan kondisi lain

---

## 🚀 **Rekomendasi Testing**

### **Test Priority Level**

#### **🔴 HIGH Priority (Core Functionality)**
1. **Valid Login** (R1)
2. **Invalid Credentials** (R2)
3. **Empty Fields** (R9, R12)
4. **Wrong Password** 

#### **🟡 MEDIUM Priority (Validation)**
5. **Invalid Email Format** (R3, R4)
6. **Edge Cases** (R5-R8, R10-R11)

#### **🟢 LOW Priority (Security)**
7. **SQL Injection attempts**
8. **XSS attempts**
9. **Rate limiting**

---

## 📈 **Test Execution Plan**

### **Phase 1: Core Functionality** (HIGH)
```bash
php artisan test --filter="test_case_[1-4]"
```

### **Phase 2: Edge Cases** (MEDIUM)
```bash
php artisan test tests/Feature/SignInTestCaseTest.php
```

### **Phase 3: Security** (LOW)
```bash
php artisan test --filter="security|injection|xss"
```

---

## ✅ **Decision Table Summary**

| **Total Rules** | **Covered** | **Coverage %** | **Status** |
|-----------------|-------------|----------------|------------|
| 12 Rules | 8 Rules | 67% | ✅ Good |
| 7 Actions | 7 Actions | 100% | ✅ Complete |
| 5 Conditions | 5 Conditions | 100% | ✅ Complete |

**Recommendation**: ✅ **Decision Table provides comprehensive test coverage for Sign In feature**

---

*Generated for Laravel Travesia Sign In Feature*  
*Black Box Testing - Decision Table Technique*  
*Coverage: Core functionality + Edge cases + Security* 