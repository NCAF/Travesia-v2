<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Driver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;


class SignInTestCaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Case 1: Login sukses dengan kredensial valid
     * Input: Email: User@gmail.com, Password: User1234
     * Expected: Menampilkan halaman Dashboard
     * Status: Normal
     */
    public function test_case_1_successful_login_with_valid_credentials()
    {
        // Arrange: Create user with specific test case credentials
        $user = User::create([
            'nama' => 'Test User',
            'email' => 'User@gmail.com', // Exact email from test case
            'password' => Hash::make('User1234'), // Exact password from test case
            'role' => 'user'
        ]);

        // Act: Submit login form with test case data
        $response = $this->post(route('login'), [
            'email' => 'User@gmail.com',
            'password' => 'User1234'
        ]);

        // Assert: Should redirect to dashboard
        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticated();
        $this->assertEquals($user->id, auth()->id());
    }

    /**
     * Test Case 2: Email salah (format angka)
     * Input: Email: 123456@gmail.com, Password: User1234
     * Expected: Sistem menampilkan pesan kesalahan "Email atau password salah"
     * Status: Alternate
     */
    public function test_case_2_login_with_invalid_email_format()
    {
        // Arrange: Create user with valid email for comparison
        User::create([
            'nama' => 'Test User',
            'email' => 'User@gmail.com',
            'password' => Hash::make('User1234'),
            'role' => 'user'
        ]);

        // Act: Try login with numeric email (different from registered email)
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => '!@#@gmail.com', // Different email from test case
            'password' => 'User1234'
        ]);

        // Assert: Should show error message and redirect back to login
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Email atau password salah.');
        $this->assertGuest();
    }

    /**
     * Test Case 3: Password salah
     * Input: Email: User@gmail.com, Password: User3210
     * Expected: Sistem menampilkan pesan kesalahan "Email atau password salah"
     * Status: Alternate
     */
    public function test_case_3_login_with_wrong_password()
    {
        // Arrange: Create user with correct email but different password
        User::create([
            'nama' => 'Test User',
            'email' => 'User@gmail.com',
            'password' => Hash::make('User1234'), // Correct password
            'role' => 'user'
        ]);

        // Act: Try login with wrong password
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'User@gmail.com',
            'password' => 'User3211' // Wrong password from test case
        ]);

        // Assert: Should show error message and redirect back to login
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Email atau password salah.');
        $this->assertGuest();
    }

    /**
     * Test Case 4: Field kosong (required validation)
     * Input: - (empty fields)
     * Expected: Sistem menampilkan "The email field is required" dan "The password field is required"
     * Status: Alternate
     */
    public function test_case_4_login_with_empty_required_fields()
    {
        // Act: Submit login form with empty fields
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => '',
            'password' => ''
        ]);

        // Assert: Should show validation errors for required fields
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors([
            'email' => 'The email field is required.',
            'password' => 'The password field is required.'
        ]);
        $this->assertGuest();
    }

    /**
     * Additional Test Case: Driver login test
     * Testing driver authentication separately as shown in original implementation
     */
    public function test_case_driver_login_with_valid_credentials()
    {
        // Arrange: Create driver
        $driver = Driver::create([
            'name' => 'Test Driver',
            'email' => 'driver@gmail.com',
            'password' => Hash::make('Driver1234'),
            'role' => 'driver'
        ]);

        // Act: Submit login form
        $response = $this->post(route('login'), [
            'email' => 'driver@gmail.com',
            'password' => 'Driver1234'
        ]);

        // Assert: Should redirect to driver destination list
        $response->assertRedirect(route('driver.destination-list'));
        $response->assertSessionHas([
            'driver_id' => $driver->id,
            'driver_name' => $driver->name,
            'driver_email' => $driver->email,
            'is_driver' => true
        ]);
    }

    /**
     * Test Case: Email validation format
     * Testing various email format validations
     */
    public function test_case_email_format_validation()
    {
        $invalidEmails = [
            'invalid-email',           // No @ symbol
            'invalid@',               // Missing domain
            '@invalid.com',           // Missing local part
            'invalid..email@test.com', // Double dots
            'invalid email@test.com'   // Space in email
        ];

        foreach ($invalidEmails as $email) {
            $response = $this->from(route('login'))->post(route('login'), [
                'email' => $email,
                'password' => 'User1234'
            ]);

            $response->assertRedirect(route('login'));
            $response->assertSessionHasErrors(['email']);
        }
    }

    /**
     * Test Case: Case sensitivity test
     * Testing if email is case insensitive but password is case sensitive
     */
    public function test_case_email_case_insensitive_password_case_sensitive()
    {
        // Arrange: Create user
        User::create([
            'nama' => 'Test User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('User1234'),
            'role' => 'user'
        ]);

        // Test 1: Email case insensitive should work
        $response = $this->post(route('login'), [
            'email' => 'USER@GMAIL.COM', // Different case
            'password' => 'User1234'
        ]);
        $response->assertRedirect(route('user.dashboard'));
        
        // Logout for next test
        auth()->logout();

        // Test 2: Password case sensitive should fail
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'user@gmail.com',
            'password' => 'USER1234' // Different case
        ]);
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Email atau password salah.');
    }

    /**
     * Test Case: SQL Injection Prevention
     * Testing that login is secure against SQL injection attempts
     */
    public function test_case_sql_injection_prevention()
    {
        $maliciousInputs = [
            "'; DROP TABLE users; --",
            "' OR '1'='1",
            "admin'--",
            "' OR 1=1#"
        ];

        foreach ($maliciousInputs as $maliciousInput) {
            $response = $this->from(route('login'))->post(route('login'), [
                'email' => $maliciousInput,
                'password' => $maliciousInput
            ]);

            // Should either redirect with error or validation error
            $this->assertTrue(
                $response->isRedirect() && 
                ($response->getSession()->has('error') || $response->getSession()->has('errors'))
            );
            $this->assertGuest();
        }
    }

    /**
     * Test Case: XSS Prevention
     * Testing that login prevents XSS attacks
     */
    public function test_case_xss_prevention()
    {
        $xssInputs = [
            '<script>alert("XSS")</script>',
            '<img src="x" onerror="alert(1)">',
            'javascript:alert(1)',
            '<svg onload=alert(1)>'
        ];

        foreach ($xssInputs as $xssInput) {
            $response = $this->from(route('login'))->post(route('login'), [
                'email' => $xssInput,
                'password' => 'password'
            ]);

            // Should handle XSS input safely
            $response->assertRedirect(route('login'));
            $this->assertGuest();
        }
    }
} 