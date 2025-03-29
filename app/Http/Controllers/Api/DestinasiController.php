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

        $destinasi = new Destinasi();
        $destinasi->user_id = Auth::id();
        $destinasi->travel_name = $request->travel_name;
        $destinasi->check_point = $request->check_point;
        $destinasi->end_point = $request->end_point;
        $destinasi->vehicle_type = $request->vehicle_type;
        $destinasi->plate_number = $request->plate_number;
        $destinasi->number_of_seats = $request->number_of_seats;
        $destinasi->price = $request->price;
        $destinasi->deskripsi = $request->deskripsi;
        $destinasi->foto = $image->uploadImage($request->file('foto'));
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