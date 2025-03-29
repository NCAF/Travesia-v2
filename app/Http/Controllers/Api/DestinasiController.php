<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Destinasi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ImageController;

class DestinasiController extends Controller
{
    /**
     * Display a listing of the resource for authenticated user.
     */

     // Destination List
     public function destinationList(){
        $destinasi = Destinasi::all();
        return view('app.driver.destination-list', compact('destinasi'));
     }

     public function create(){
        return view('app.driver.add-destination');
     }

    //  Create Destinasi
    public function createPost(Request $request){
        $validator = Validator::make($request->all(), [
            'travel_name' => 'required',
            'start_date' => 'required|date',
            'check_point' => 'required',
            'end_point' => 'required',
            'vehicle_type' => 'required',
            'plate_number' => 'required',
            'number_of_seats' => 'required',
            'price' => 'required',
            'foto' => 'required',
            'deskripsi' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('driver.add-destination')->withErrors($validator)->withInput();
        }

        $image = new ImageController();

        // Untuk driver, gunakan driver_id dari session
        if (session()->has('is_driver') && session()->get('is_driver')) {
            $user_id = session()->get('driver_id');
            
            if (!$user_id) {
                \Log::error('Driver ID null dari session', [
                    'session' => session()->all()
                ]);
                
                return redirect()->route('driver.add-destination')
                    ->with('error', 'Gagal mendapatkan ID driver. Silahkan login ulang.')
                    ->withInput();
            }
        } else {
            // Untuk user biasa (jika dibutuhkan)
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
            }
            
            $user_id = Auth::id();
            
            if (!$user_id) {
                \Log::error('User ID null saat membuat destinasi', [
                    'user' => Auth::user(),
                    'is_logged_in' => Auth::check()
                ]);
                
                return redirect()->route('driver.add-destination')
                    ->with('error', 'Gagal mendapatkan ID pengguna. Silahkan login ulang.')
                    ->withInput();
            }
        }

        $destinasi = new Destinasi();
        $destinasi->user_id = $user_id;
        $destinasi->kode_destinasi = 'DST-' . strtoupper(Str::random(8));
        $destinasi->travel_name = $request->travel_name;
        $destinasi->start_date = $request->start_date;
        $destinasi->check_point = $request->check_point;
        $destinasi->end_point = $request->end_point;
        $destinasi->vehicle_type = $request->vehicle_type;
        $destinasi->plate_number = $request->plate_number;
        $destinasi->number_of_seats = $request->number_of_seats;
        $destinasi->price = $request->price;
        $destinasi->deskripsi = $request->deskripsi;
        $destinasi->foto = $image->uploadImage($request->file('foto'));
        $destinasi->status = Destinasi::STATUS_ORDERABLE;
        $destinasi->save();

        return redirect()->route('driver.destination-list')->with('success', 'Berhasil menambahkan destinasi.');
    }

    // View Destinasi
    public function show(Request $request){
        $id = $request->query('id');
        $destinasi = Destinasi::findOrFail($id);
        return view('app.driver.detail-destination', compact('destinasi'));
    }
}