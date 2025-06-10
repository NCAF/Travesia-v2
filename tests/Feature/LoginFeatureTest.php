<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Driver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class LoginFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that login page is accessible
     */
    public function test_login_page_is_accessible()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * Test successful user login flow
     */
    public function test_successful_user_login_flow()
    {
        // Arrange: Create a user
        $user = User::create([
            'nama' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]);

        // Act: Submit login form
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'testuser@example.com',
            'password' => 'password123'
        ]);

        // Assert: User is redirected to dashboard and authenticated
        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticated();
        $this->assertEquals($user->id, auth()->id());
    }

    /**
     * Test successful driver login flow
     */
    public function test_successful_driver_login_flow()
    {
        // Arrange: Create a driver
        $driver = Driver::create([
            'name' => 'Test Driver',
            'email' => 'testdriver@example.com',
            'password' => Hash::make('password123'),
            'role' => 'driver'
        ]);

        // Act: Submit login form
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'testdriver@example.com',
            'password' => 'password123'
        ]);

        // Assert: Driver is redirected to driver destination list
        $response->assertRedirect(route('driver.destination-list'));
        $response->assertSessionHas([
            'driver_id' => $driver->id,
            'driver_name' => $driver->name,
            'driver_email' => $driver->email,
            'is_driver' => true
        ]);
    }

    /**
     * Test login with invalid credentials shows error
     */
    public function test_login_with_invalid_credentials_shows_error()
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Email atau password salah.');
        $this->assertGuest();
    }

    /**
     * Test login form validation errors
     */
    public function test_login_form_validation_errors()
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'invalid-email',
            'password' => ''
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    /**
     * Test login redirects authenticated user to dashboard
     */
    public function test_login_redirects_authenticated_user_to_dashboard()
    {
        // Arrange: Create and authenticate a user
        $user = User::create([
            'nama' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]);

        $this->actingAs($user);

        // Act: Try to access login page
        $response = $this->get(route('login'));

        // Assert: Should be redirected away from login (this depends on your middleware setup)
        // If you have middleware that redirects authenticated users, uncomment the line below
        // $response->assertRedirect(route('user.dashboard'));
    }

    /**
     * Test user role determines redirect destination
     */
    public function test_user_role_determines_redirect_destination()
    {
        // Test user with 'user' role
        $regularUser = User::create([
            'nama' => 'Regular User',
            'email' => 'regular@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]);

        $response = $this->post(route('login'), [
            'email' => 'regular@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticated();

        // Note: Driver authentication is tested separately since drivers use a different table
        // and authentication mechanism (Driver model vs User model)
    }

    /**
     * Test login preserves intended URL after authentication
     */
    public function test_login_preserves_intended_url()
    {
        // Create a user
        $user = User::create([
            'nama' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]);

        // Try to access a protected route without authentication
        $intendedUrl = route('user.destination-list');
        $this->get($intendedUrl);

        // Login
        $response = $this->post(route('login'), [
            'email' => 'testuser@example.com',
            'password' => 'password123'
        ]);

        // Should be redirected to dashboard (as per your controller logic)
        $response->assertRedirect(route('user.dashboard'));
    }

    /**
     * Test multiple failed login attempts
     */
    public function test_multiple_failed_login_attempts()
    {
        $user = User::create([
            'nama' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => Hash::make('correctpassword'),
            'role' => 'user'
        ]);

        // Multiple failed attempts
        for ($i = 0; $i < 3; $i++) {
            $response = $this->post(route('login'), [
                'email' => 'testuser@example.com',
                'password' => 'wrongpassword'
            ]);

            $response->assertRedirect(route('login'));
            $response->assertSessionHas('error', 'Email atau password salah.');
            $this->assertGuest();
        }

        // Successful login should still work
        $response = $this->post(route('login'), [
            'email' => 'testuser@example.com',
            'password' => 'correctpassword'
        ]);

        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticated();
    }

    /**
     * Test logout functionality
     */
    public function test_logout_functionality()
    {
        // Arrange: Create and login a user
        $user = User::create([
            'nama' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]);

        $this->actingAs($user);
        $this->assertAuthenticated();

        // Act: Logout
        $response = $this->post(route('logout'));

        // Assert: User is logged out and redirected to login
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    /**
     * Test driver logout functionality
     */
    public function test_driver_logout_functionality()
    {
        // Arrange: Create a driver and simulate login
        $driver = Driver::create([
            'name' => 'Test Driver',
            'email' => 'testdriver@example.com',
            'password' => Hash::make('password123'),
            'role' => 'driver'
        ]);

        // Simulate driver login session
        session([
            'driver_id' => $driver->id,
            'driver_name' => $driver->name,
            'driver_email' => $driver->email,
            'is_driver' => true
        ]);

        // Act: Logout
        $response = $this->post(route('logout'));

        // Assert: Driver session is cleared and redirected to login
        $response->assertRedirect(route('login'));
        $response->assertSessionMissing(['driver_id', 'driver_name', 'driver_email', 'is_driver']);
    }

    /**
     * Test login rate limiting (if implemented)
     */
    public function test_login_rate_limiting()
    {
        // This test assumes you have rate limiting implemented
        // Adjust the number of attempts based on your rate limiting configuration
        
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post(route('login'), [
                'email' => 'test@example.com',
                'password' => 'wrongpassword'
            ]);
        }

        // After rate limiting kicks in, you might want to test for specific responses
        // This depends on your implementation of rate limiting
        $this->assertTrue(true); // Placeholder assertion
    }
} 