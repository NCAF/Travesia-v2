<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required',
            'nama' => 'required',
            'email' => 'required|email',
            'telp' => 'required',
            'gender' => 'required',
            'alamat' => 'required',
            'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/|confirmed',
            'password_confirmation' => 'required',
            'foto' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
            'email' => ':attribute harus berupa email yang valid.',
            'min' => 'panjang :attribute minimal :min karakter.',
            'regex' => ':attribute harus mengandung minimal satu huruf kecil, satu huruf besar, dan satu angka.',
            'confirmed' => 'Password dan konfirmasi password tidak sama.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => Str::ucfirst($validator->errors()->first()),
                'data' => null
            ]);
        }

        $base64Data = $request->input('foto');

        $cek_email = User::where('email', $request->email)->get()->count();
        if ($cek_email > 0) {
            return response()->json([
                'error' => true,
                'message' => "Email telah terpakai. Silahkan hubungi CS untuk konfirmasi jika merasa tidak mendaftar.",
                'data' => null
            ]);
        }

        $file_type = explode(';base64,', $base64Data);
        $file_type = explode('data:', $file_type[0]);
        $file_type = explode('/', $file_type[1]);
        $data_type = $file_type[0];
        $app_type = $file_type[1];
        $file_convert = str_replace("data:$data_type/" . $app_type . ';base64,', '', $base64Data);
        $file_convert = str_replace(' ', '+', $file_convert);

        $fotoData = base64_decode($file_convert);

        // Simpan file sementara
        $uploadPath = public_path('uploads/users');
        File::makeDirectory($uploadPath, 0755, true, true);
        $filename = $this->generateRandomString(33).time() . ".".$app_type;
        $filePath = $uploadPath . '/' . $filename;
        file_put_contents($filePath, $fotoData);

        $user = User::create([
            'role' => $request->role,
            'nama' => $request->nama,
            'email' => $request->email,
            'telp' => $request->telp,
            'gender' => $request->gender,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
            'foto' => '/uploads/users/'.$filename,
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Berhasil melakukan registrasi. Silahkan Login.',
            'data' => null
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
            'email' => 'alamat email pada kolom :attribute tidak valid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => Str::ucfirst($validator->errors()->first()),
                'data' => null
            ]);
        }

        $user = User::select('*')->where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken("auth-token")->plainTextToken;
            Auth::login($user);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Pastikan email dan password anda benar.',
                'data' => null
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => 'Berhasil login.',
            'data' => [
                'token' => $token,
                'user' => $user
            ]
        ]);
    }

    public function logout(Request $request)
    {
        if(method_exists(auth()->user()->currentAccessToken(), 'delete')) {
            auth()->user()->currentAccessToken()->delete();
        }
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}
