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
        
        // First try to authenticate as a regular user
        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
           
            if(Auth::user()->role == 'user'){
                return redirect()->route('user.dashboard');
            }else{
                return redirect()->route('driver.dashboard');
            }
        }
        
        // Try to authenticate as a driver
        $driver = Driver::where('email', $request->email)->first();
        
        if ($driver && Hash::check($request->password, $driver->password)) {
            // Store driver info in session
            $request->session()->put('driver_id', $driver->id);
            $request->session()->put('driver_name', $driver->name);
            $request->session()->put('driver_email', $driver->email);
            $request->session()->put('driver_role', $driver->role);
            $request->session()->put('is_driver', true);
            
            return redirect()->route('driver.destination-list');
        }

        return redirect()->route('login')->with('error', 'Email atau password salah.');
    }

    public function registerDriver(){
        return view('auth.register-driver');
    }

    public function registerDriverPost(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:driver',
            'password' => 'required|string|min:8|confirmed',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

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
