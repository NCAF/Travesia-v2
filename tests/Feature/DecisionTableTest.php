<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Driver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

/**
 * Black Box Testing - Decision Table Implementation
 * 
 * Testing Sign In feature berdasarkan Decision Table dengan 12 rules
 * yang mencakup semua kombinasi kondisi input dan output
 */
class DecisionTableTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup test user untuk testing
        User::create([
            'nama' => 'Test User',
            'email' => 'User@gmail.com',
            'password' => Hash::make('User1234'),
            'role' => 'user'
        ]);
    }

    /**
     * RULE 1: All conditions TRUE
     * C1=T, C2=T, C3=T, C4=T, C5=T
     * Expected: Login berhasil, redirect ke dashboard
     */
    public function test_rule_1_all_conditions_true_login_success()
    {
        $response = $this->post(route('login'), [
            'email' => 'User@gmail.com',    // C1=T, C3=T, C4=T
            'password' => 'User1234'        // C2=T, C5=T
        ]);

        // A1: Login berhasil, A2: Redirect ke dashboard
        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticated();
    }

    /**
     * RULE 2: Email not registered
     * C1=T, C2=T, C3=T, C4=F, C5=N/A
     * Expected: Error "Email atau password salah"
     */
    public function test_rule_2_email_not_registered()
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => '123456@gmail.com',  // C1=T, C3=T, C4=F
            'password' => 'User1234'        // C2=T
        ]);

        // A3: Show error, A7: Stay on login page
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Email atau password salah.');
        $this->assertGuest();
    }

    /**
     * RULE 3: Invalid email format but registered email exists
     * C1=T, C2=T, C3=F, C4=T, C5=N/A
     * Expected: Email format validation error
     */
    public function test_rule_3_invalid_email_format_registered_exists()
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'invalid-email',     // C1=T, C3=F
            'password' => 'User1234'        // C2=T
        ]);

        // A6: Invalid email format, A7: Stay on login page
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * RULE 4: Invalid email format and not registered
     * C1=T, C2=T, C3=F, C4=F, C5=N/A
     * Expected: Email format validation error
     */
    public function test_rule_4_invalid_email_format_not_registered()
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'invalid@',          // C1=T, C3=F, C4=F
            'password' => 'User1234'        // C2=T
        ]);

        // A6: Invalid email format, A7: Stay on login page
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * RULE 5: Valid email registered, empty password
     * C1=T, C2=F, C3=T, C4=T, C5=N/A
     * Expected: Password required validation error
     */
    public function test_rule_5_valid_email_empty_password()
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'User@gmail.com',    // C1=T, C3=T, C4=T
            'password' => ''                // C2=F
        ]);

        // A5: Password required, A7: Stay on login page
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    /**
     * RULE 6: Valid email not registered, empty password
     * C1=T, C2=F, C3=T, C4=F, C5=N/A
     * Expected: Password required validation error
     */
    public function test_rule_6_valid_email_not_registered_empty_password()
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'notregistered@gmail.com', // C1=T, C3=T, C4=F
            'password' => ''                       // C2=F
        ]);

        // A5: Password required, A7: Stay on login page
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    /**
     * RULE 7: Invalid email format registered, empty password
     * C1=T, C2=F, C3=F, C4=T, C5=N/A
     * Expected: Multiple validation errors
     */
    public function test_rule_7_invalid_email_registered_empty_password()
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'invalid-email',     // C1=T, C3=F
            'password' => ''                // C2=F
        ]);

        // A5: Password required, A6: Invalid email, A7: Stay on login page
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    /**
     * RULE 8: Invalid email format not registered, empty password
     * C1=T, C2=F, C3=F, C4=F, C5=N/A
     * Expected: Multiple validation errors
     */
    public function test_rule_8_invalid_email_not_registered_empty_password()
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'invalid@',          // C1=T, C3=F, C4=F
            'password' => ''                // C2=F
        ]);

        // A5: Password required, A6: Invalid email, A7: Stay on login page
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    /**
     * RULE 9: Empty email, valid password
     * C1=F, C2=T, C3=N/A, C4=N/A, C5=N/A
     * Expected: Email required validation error
     */
    public function test_rule_9_empty_email_valid_password()
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => '',                  // C1=F
            'password' => 'User1234'        // C2=T
        ]);

        // A4: Email required, A7: Stay on login page
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * RULE 10: Empty email, valid password (invalid format context)
     * C1=F, C2=T, C3=F, C4=N/A, C5=N/A
     * Expected: Email required validation error
     */
    public function test_rule_10_empty_email_valid_password_invalid_context()
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => '',                  // C1=F, C3=F
            'password' => 'User1234'        // C2=T
        ]);

        // A4: Email required, A7: Stay on login page
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * RULE 11: Empty email, empty password (valid format context)
     * C1=F, C2=F, C3=T, C4=N/A, C5=N/A
     * Expected: Both email and password required
     */
    public function test_rule_11_empty_email_empty_password_valid_context()
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => '',                  // C1=F, C3=T (context)
            'password' => ''                // C2=F
        ]);

        // A4: Email required, A5: Password required, A7: Stay on login page
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    /**
     * RULE 12: Empty email, empty password (invalid format context)
     * C1=F, C2=F, C3=F, C4=N/A, C5=N/A
     * Expected: Both email and password required
     */
    public function test_rule_12_empty_email_empty_password_invalid_context()
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => '',                  // C1=F, C3=F
            'password' => ''                // C2=F
        ]);

        // A4: Email required, A5: Password required, A7: Stay on login page
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    /**
     * BONUS RULE: Wrong Password (based on original test case 3)
     * C1=T, C2=T, C3=T, C4=T, C5=F
     * Expected: Error "Email atau password salah"
     */
    public function test_bonus_rule_wrong_password()
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'User@gmail.com',    // C1=T, C3=T, C4=T
            'password' => 'User3210'        // C2=T, C5=F (wrong password)
        ]);

        // A3: Show error, A7: Stay on login page
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Email atau password salah.');
        $this->assertGuest();
    }

    /**
     * Edge Case: Driver Login Test
     * Testing driver authentication flow
     */
    public function test_edge_case_driver_login()
    {
        // Create driver for testing
        $driver = Driver::create([
            'name' => 'Test Driver',
            'email' => 'driver@gmail.com',
            'password' => Hash::make('Driver1234'),
            'role' => 'driver'
        ]);

        $response = $this->post(route('login'), [
            'email' => 'driver@gmail.com',
            'password' => 'Driver1234'
        ]);

        // Should redirect to driver destination list
        $response->assertRedirect(route('driver.destination-list'));
        $response->assertSessionHas('is_driver', true);
    }

    /**
     * Edge Case: Email Case Insensitive
     * Testing that email login is case insensitive
     */
    public function test_edge_case_email_case_insensitive()
    {
        $response = $this->post(route('login'), [
            'email' => 'USER@GMAIL.COM',    // Different case
            'password' => 'User1234'
        ]);

        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticated();
    }

    /**
     * Edge Case: Password Case Sensitive
     * Testing that password is case sensitive
     */
    public function test_edge_case_password_case_sensitive()
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'User@gmail.com',
            'password' => 'USER1234'        // Different case - should fail
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Email atau password salah.');
        $this->assertGuest();
    }

    /**
     * Security Test: SQL Injection Prevention
     */
    public function test_security_sql_injection_prevention()
    {
        $maliciousInputs = [
            "'; DROP TABLE users; --",
            "' OR '1'='1",
            "admin'--"
        ];

        foreach ($maliciousInputs as $maliciousInput) {
            $response = $this->from(route('login'))->post(route('login'), [
                'email' => $maliciousInput,
                'password' => $maliciousInput
            ]);

            // Should safely handle malicious input
            $this->assertTrue($response->isRedirect());
            $this->assertGuest();
        }
    }

    /**
     * Security Test: XSS Prevention
     */
    public function test_security_xss_prevention()
    {
        $xssInputs = [
            '<script>alert("XSS")</script>',
            '<img src="x" onerror="alert(1)">',
            'javascript:alert(1)'
        ];

        foreach ($xssInputs as $xssInput) {
            $response = $this->from(route('login'))->post(route('login'), [
                'email' => $xssInput,
                'password' => 'password'
            ]);

            // Should safely handle XSS attempts
            $response->assertRedirect(route('login'));
            $this->assertGuest();
        }
    }
} 