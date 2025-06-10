<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Driver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful user login with valid credentials
     */
    public function test_user_can_login_with_valid_credentials()
    {
        // Arrange: Create a user
        $user = User::create([
            'nama' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]);

        // Act: Attempt to login
        $response = $this->post(route('login'), [
            'email' => 'john@example.com',
            'password' => 'password123'
        ]);

        // Assert: Check if login was successful
        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test driver login with valid credentials
     */
    public function test_driver_can_login_with_valid_credentials()
    {
        // Arrange: Create a driver
        $driver = Driver::create([
            'name' => 'Driver Smith',
            'email' => 'driver@example.com',
            'password' => Hash::make('password123'),
            'role' => 'driver'
        ]);

        // Act: Attempt to login as driver
        $response = $this->post(route('login'), [
            'email' => 'driver@example.com',
            'password' => 'password123'
        ]);

        // Assert: Check if driver login was successful
        $response->assertRedirect(route('driver.destination-list'));
        $response->assertSessionHas('driver_id', $driver->id);
        $response->assertSessionHas('driver_name', $driver->name);
        $response->assertSessionHas('driver_email', $driver->email);
        $response->assertSessionHas('is_driver', true);
    }

    /**
     * Test login fails with invalid email
     */
    public function test_login_fails_with_invalid_email()
    {
        // Act: Attempt to login with invalid email
        $response = $this->post(route('login'), [
            'email' => 'nonexistent@example.com',
            'password' => 'password123'
        ]);

        // Assert: Check if login failed
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Email atau password salah.');
        $this->assertGuest();
    }

    /**
     * Test login fails with invalid password
     */
    public function test_login_fails_with_invalid_password()
    {
        // Arrange: Create a user
        User::create([
            'nama' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]);

        // Act: Attempt to login with wrong password
        $response = $this->post(route('login'), [
            'email' => 'john@example.com',
            'password' => 'wrongpassword'
        ]);

        // Assert: Check if login failed
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Email atau password salah.');
        $this->assertGuest();
    }

    /**
     * Test login validation fails with empty fields
     */
    public function test_login_validation_fails_with_empty_fields()
    {
        // Act: Attempt to login with empty fields
        $response = $this->post(route('login'), [
            'email' => '',
            'password' => ''
        ]);

        // Assert: Check validation errors
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    /**
     * Test login validation fails with invalid email format
     */
    public function test_login_validation_fails_with_invalid_email_format()
    {
        // Act: Attempt to login with invalid email format
        $response = $this->post(route('login'), [
            'email' => 'invalid-email',
            'password' => 'password123'
        ]);

        // Assert: Check validation errors
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * Test user redirected to correct dashboard based on role
     */
    public function test_user_redirected_to_correct_dashboard_based_on_role()
    {
        // Note: Based on the users table migration, role only supports 'user' enum value
        // For this test, we'll test normal user behavior since driver auth is handled separately
        $regularUser = User::create([
            'nama' => 'Regular User',
            'email' => 'regular@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]);

        // Act: Attempt to login
        $response = $this->post(route('login'), [
            'email' => 'regular@example.com',
            'password' => 'password123'
        ]);

        // Assert: Check if redirected to user dashboard
        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticatedAs($regularUser);
    }

    /**
     * Test session is regenerated on successful login
     */
    public function test_session_is_regenerated_on_successful_login()
    {
        // Arrange: Create a user
        $user = User::create([
            'nama' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]);

        // Store the initial session ID
        $initialSessionId = session()->getId();

        // Act: Login
        $response = $this->post(route('login'), [
            'email' => 'john@example.com',
            'password' => 'password123'
        ]);

        // Assert: Session should be regenerated
        $this->assertNotEquals($initialSessionId, session()->getId());
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test driver login stores correct session data
     */
    public function test_driver_login_stores_correct_session_data()
    {
        // Arrange: Create a driver
        $driver = Driver::create([
            'name' => 'Driver Smith',
            'email' => 'driver@example.com',
            'password' => Hash::make('password123'),
            'role' => 'driver'
        ]);

        // Act: Login as driver
        $response = $this->post(route('login'), [
            'email' => 'driver@example.com',
            'password' => 'password123'
        ]);

        // Assert: Check all session data is stored correctly
        $response->assertSessionHas('driver_id', $driver->id);
        $response->assertSessionHas('driver_name', $driver->name);
        $response->assertSessionHas('driver_email', $driver->email);
        $response->assertSessionHas('driver_role', $driver->role);
        $response->assertSessionHas('is_driver', true);
    }

    /**
     * Test user login attempts don't interfere with driver login
     */
    public function test_user_and_driver_login_are_independent()
    {
        // Arrange: Create both user and driver with same email and password
        $user = User::create([
            'nama' => 'John Doe',
            'email' => 'same@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]);

        $driver = Driver::create([
            'name' => 'Driver Smith',
            'email' => 'same@example.com',
            'password' => Hash::make('password123'),
            'role' => 'driver'
        ]);

        // Act: Login should authenticate as user first (based on controller logic)
        $response = $this->post(route('login'), [
            'email' => 'same@example.com',
            'password' => 'password123'
        ]);

        // Assert: Should login as user, not driver
        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticatedAs($user);
        $response->assertSessionMissing('is_driver');
    }

    /**
     * Test case sensitivity in login credentials
     */
    public function test_login_is_case_sensitive_for_password()
    {
        // Arrange: Create a user
        User::create([
            'nama' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('Password123'),
            'role' => 'user'
        ]);

        // Act: Try login with different case password
        $response = $this->post(route('login'), [
            'email' => 'john@example.com',
            'password' => 'password123' // lowercase 'p'
        ]);

        // Assert: Login should fail
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Email atau password salah.');
        $this->assertGuest();
    }
}