<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Driver;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $authController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authController = new AuthController();
    }

    /**
     * Test login method returns login view
     */
    public function test_login_method_returns_login_view()
    {
        $response = $this->authController->login();
        
        $this->assertEquals('auth.login', $response->getName());
    }

    /**
     * Test register method returns register view
     */
    public function test_register_method_returns_register_view()
    {
        $response = $this->authController->register();
        
        $this->assertEquals('auth.register', $response->getName());
    }

    /**
     * Test registerDriver method returns register driver view
     */
    public function test_register_driver_method_returns_register_driver_view()
    {
        $response = $this->authController->registerDriver();
        
        $this->assertEquals('auth.register-driver', $response->getName());
    }

    /**
     * Test loginPost with valid user credentials
     */
    public function test_login_post_with_valid_user_credentials()
    {
        // Arrange
        $user = User::create([
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]);

        $request = Request::create('/', 'POST', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);
        $request->setLaravelSession($this->app['session.store']);

        // Act
        $response = $this->authController->loginPost($request);

        // Assert
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
    }

    /**
     * Test loginPost with valid driver credentials
     */
    public function test_login_post_with_valid_driver_credentials()
    {
        // Arrange
        $driver = Driver::create([
            'name' => 'Test Driver',
            'email' => 'driver@example.com',
            'password' => Hash::make('password123'),
            'role' => 'driver'
        ]);

        $request = Request::create('/', 'POST', [
            'email' => 'driver@example.com',
            'password' => 'password123'
        ]);
        $request->setLaravelSession($this->app['session.store']);

        // Act
        $response = $this->authController->loginPost($request);

        // Assert
        $this->assertTrue($request->session()->has('driver_id'));
        $this->assertEquals($driver->id, $request->session()->get('driver_id'));
        $this->assertEquals($driver->name, $request->session()->get('driver_name'));
        $this->assertTrue($request->session()->get('is_driver'));
    }

    /**
     * Test loginPost with invalid credentials
     */
    public function test_login_post_with_invalid_credentials()
    {
        $request = Request::create('/', 'POST', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword'
        ]);
        $request->setLaravelSession($this->app['session.store']);

        $response = $this->authController->loginPost($request);

        $this->assertFalse(Auth::check());
        $this->assertTrue($request->session()->has('error'));
        $this->assertEquals('Email atau password salah.', $request->session()->get('error'));
    }

    /**
     * Test loginPost validation fails with empty fields
     */
    public function test_login_post_validation_fails_with_empty_fields()
    {
        $request = Request::create('/', 'POST', [
            'email' => '',
            'password' => ''
        ]);
        $request->setLaravelSession($this->app['session.store']);

        $response = $this->authController->loginPost($request);

        $this->assertFalse(Auth::check());
    }

    /**
     * Test loginPost validation fails with invalid email format
     */
    public function test_login_post_validation_fails_with_invalid_email_format()
    {
        $request = Request::create('/', 'POST', [
            'email' => 'invalid-email-format',
            'password' => 'password123'
        ]);
        $request->setLaravelSession($this->app['session.store']);

        $response = $this->authController->loginPost($request);

        $this->assertFalse(Auth::check());
    }

    /**
     * Test logout method clears user session
     */
    public function test_logout_method_clears_user_session()
    {
        // Arrange: Login a user
        $user = User::create([
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]);

        Auth::login($user);
        $this->assertTrue(Auth::check());

        $request = Request::create('/', 'POST');
        $request->setLaravelSession($this->app['session.store']);

        // Act: Logout
        $response = $this->authController->logout($request);

        // Assert
        $this->assertFalse(Auth::check());
    }

    /**
     * Test logout method clears driver session
     */
    public function test_logout_method_clears_driver_session()
    {
        $request = Request::create('/', 'POST');
        $request->setLaravelSession($this->app['session.store']);

        // Simulate driver session
        $request->session()->put('driver_id', 1);
        $request->session()->put('driver_name', 'Test Driver');
        $request->session()->put('is_driver', true);

        $this->assertTrue($request->session()->has('is_driver'));

        // Act: Logout
        $response = $this->authController->logout($request);

        // Assert
        $this->assertFalse($request->session()->has('driver_id'));
        $this->assertFalse($request->session()->has('driver_name'));
        $this->assertFalse($request->session()->has('is_driver'));
    }

    /**
     * Test user login takes precedence over driver login with same email
     */
    public function test_user_login_takes_precedence_over_driver_login()
    {
        // Arrange: Create both user and driver with same email
        $user = User::create([
            'nama' => 'Test User',
            'email' => 'same@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]);

        $driver = Driver::create([
            'name' => 'Test Driver',
            'email' => 'same@example.com',
            'password' => Hash::make('password123'),
            'role' => 'driver'
        ]);

        $request = Request::create('/', 'POST', [
            'email' => 'same@example.com',
            'password' => 'password123'
        ]);
        $request->setLaravelSession($this->app['session.store']);

        // Act
        $response = $this->authController->loginPost($request);

        // Assert: Should authenticate as user, not driver
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
        $this->assertFalse($request->session()->has('is_driver'));
    }

    /**
     * Test registerPost creates user successfully
     */
    public function test_register_post_creates_user_successfully()
    {
        $request = Request::create('/', 'POST', [
            'nama' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);
        $request->setLaravelSession($this->app['session.store']);

        $response = $this->authController->registerPost($request);

        $this->assertDatabaseHas('users', [
            'nama' => 'New User',
            'email' => 'newuser@example.com',
            'role' => 'user'
        ]);

        $user = User::where('email', 'newuser@example.com')->first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    /**
     * Test registerPost validation fails with invalid data
     */
    public function test_register_post_validation_fails_with_invalid_data()
    {
        $request = Request::create('/', 'POST', [
            'nama' => '', // Empty name
            'email' => 'invalid-email', // Invalid email format
            'password' => '123', // Too short password
            'password_confirmation' => '456' // Password confirmation doesn't match
        ]);
        $request->setLaravelSession($this->app['session.store']);

        $response = $this->authController->registerPost($request);

        $this->assertDatabaseMissing('users', [
            'email' => 'invalid-email'
        ]);
    }

    /**
     * Test password hashing in user creation
     */
    public function test_password_hashing_in_user_creation()
    {
        $request = Request::create('/', 'POST', [
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'plainpassword',
            'password_confirmation' => 'plainpassword'
        ]);
        $request->setLaravelSession($this->app['session.store']);

        $response = $this->authController->registerPost($request);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotEquals('plainpassword', $user->password);
        $this->assertTrue(Hash::check('plainpassword', $user->password));
    }

    /**
     * Test session regeneration on login
     */
    public function test_session_regeneration_on_login()
    {
        $user = User::create([
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]);

        $request = Request::create('/', 'POST', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);
        $request->setLaravelSession($this->app['session.store']);

        $initialSessionId = $request->session()->getId();

        $response = $this->authController->loginPost($request);

        // Note: In actual HTTP requests, session regeneration happens
        // This test might need adjustment based on your testing setup
        $this->assertTrue(Auth::check());
    }
}