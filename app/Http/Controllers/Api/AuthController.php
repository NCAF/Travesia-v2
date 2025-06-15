<?php

namespace App\Http\Controllers\Api;
// namespace App\Http\Controllers\ImageController;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Driver;
use App\Models\User_role;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ImageController;

class AuthController extends Controller
{
    public function register(){
        return view('auth.register');
    }

    public function registerPost(Request $request){
        $validator = Validator::make($request->all(), [
            'nama' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/[a-zA-Z]/', 
            ],
            'email' => [
                'required',
                'string',
                'email',
                'min:3',
                'max:200',
                'unique:users',
                'regex:/^[a-zA-Z].*@/', // Must start with a letter
                'regex:/^[a-zA-Z0-9_.]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/', // No special characters except _.
                function ($attribute, $value, $fail) {
                    if (Driver::where('email', $value)->exists()) {
                        $fail('Email sudah digunakan oleh driver.');
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:100',
                'confirmed',
                'regex:/[a-zA-Z]/', // Must contain at least one letter
                'regex:/^[^\s]+$/', // No spaces allowed
            ],
        ], [
            'nama.required' => 'Nama lengkap harus diisi',
            'nama.min' => 'Nama minimal 3 karakter',
            'nama.max' => 'Nama maksimal 100 karakter',
            'nama.regex' => 'Nama harus mengandung huruf, tidak boleh angka saja',
            
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'email.min' => 'Email minimal 3 karakter',
            'email.max' => 'Email maksimal 200 karakter',
            'email.regex' => 'Email harus mengandung huruf dan tidak boleh ada karakter khusus',
            
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.max' => 'Password maksimal 100 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.regex' => 'Password harus mengandung huruf dan tidak boleh ada spasi',
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('register')->withErrors($validator)->withInput();
        }

        $user = User::create([
            'role' => 'user',
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return redirect()->route('login')->with('success', 'Berhasil melakukan registrasi. Silahkan Login.');
    }

    public function login(){
        return view('auth.login');
    }

    public function loginPost(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('login')->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');
        
        // PRIORITY 1: Try to authenticate as a driver first
        $driver = Driver::where('email', $request->email)->first();
        
        if ($driver && Hash::check($request->password, $driver->password)) {
            // Store driver info in session
            $request->session()->regenerate();
            $request->session()->put('driver_id', $driver->id);
            $request->session()->put('driver_name', $driver->name);
            $request->session()->put('driver_email', $driver->email);
            $request->session()->put('driver_role', $driver->role);
            $request->session()->put('is_driver', true);
            
            return redirect()->route('driver.destination-list');
        }
        
        // PRIORITY 2: Try to authenticate as a regular user
        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
           
            if(Auth::user()->role == 'user'){
                return redirect()->route('user.dashboard');
            }else{
                return redirect()->route('driver.dashboard');
            }
        }

        return redirect()->route('login')->with('error', 'Email atau password salah.');
    }

    public function registerDriver(){
        return view('auth.register-driver');
    }

    public function registerDriverPost(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\'\-\.]+$/'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                'unique:driver',
                'regex:/^[^0-9]+@/',
                function ($attribute, $value, $fail) {
                    if (User::where('email', $value)->exists()) {
                        $fail('Email sudah digunakan oleh user.');
                    }
                },
            ],
            'password' => ['required', 'string', 'min:8', 'max:100', 'same:password_confirmation', 'regex:/^\S*$/'],
            'password_confirmation' => ['required', 'string', 'min:8', 'max:100', 'regex:/^\S*$/'],
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.regex' => 'The name field must contain only letters and spaces.',
            'email.email' => 'The email must be a valid email address including @ symbol.',
            'email.regex' => 'The email address cannot contain numbers before the @ symbol.',
            'email.unique' => 'Email sudah digunakan.',
            'password.same' => 'The password and confirmation password do not match.',
            'password.max' => 'The password may not be greater than 100 characters.',
            'password.regex' => 'The password cannot contain spaces.',
            'password_confirmation.regex' => 'The password confirmation cannot contain spaces.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('driver.register-driver')
                ->withErrors($validator)
                ->withInput();
        }

        $driver = new Driver([
            'role' => 'driver',
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Upload image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $driver->image = $imageName;
        }
        
        $driver->save();

        return redirect()->route('login')->with('success', 'Berhasil melakukan registrasi. Silahkan Login.');
    }

    public function logout(Request $request){
        // Check if this is a driver logout
        if ($request->session()->has('is_driver')) {
            $request->session()->forget(['driver_id', 'driver_name', 'driver_email', 'driver_role', 'is_driver']);
        } else {
            Auth::logout();
        }
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
