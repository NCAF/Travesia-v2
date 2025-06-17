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
    public function index()
    {
        $destinations = Destinasi::select('check_point', 'end_point')
            ->distinct()
            ->get()
            ->map(function ($item) {
                return [
                    'check_point' => $item->check_point,
                    'end_point' => $item->end_point
                ];
            });

        $uniqueLocations = collect();
        foreach ($destinations as $destination) {
            $uniqueLocations->push($destination['check_point']);
            $uniqueLocations->push($destination['end_point']);
        }
        $uniqueLocations = $uniqueLocations->unique()->values();

        return view('app.user.dashboard', compact('uniqueLocations'));
    }

    // Destination List
    public function destinationList()
    {
        $destinasi = Destinasi::where('driver_id', session()->get('driver_id'))->get();
        return view('app.driver.destination-list', compact('destinasi'));
    }

    // Search for Driver's Destination List
    public function searchDriver(Request $request)
    {
        $query = Destinasi::query();
        
        // Filter by driver_id first
        $query->where('driver_id', session()->get('driver_id'));

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('travel_name', 'like', "%{$search}%")
                  ->orWhere('check_point', 'like', "%{$search}%")
                  ->orWhere('end_point', 'like', "%{$search}%");
            });
        }
        
        $destinasi = $query->get();
        return view('app.driver.destination-list', compact('destinasi'));
    }

    public function create()
    {
        $destinations = Destinasi::select('check_point', 'end_point')
            ->distinct()
            ->get();

        $checkPoints = $destinations->pluck('check_point')->unique()->values();
        $endPoints = $destinations->pluck('end_point')->unique()->values();

        return view('app.driver.add-destination', compact('checkPoints', 'endPoints'));
    }

    //  Create Destinasi
    public function createPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'travel_name' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'check_point' => 'required',
            'end_point' => 'required',
            'vehicle_type' => 'required',
            'plate_number' => 'required',
            'number_of_seats' => 'required',
            'price' => 'required',
            'link_wa_group' => 'required',
            'foto' => 'required',
            'deskripsi' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('driver.add-destination')->withErrors($validator)->withInput();
        }

        $image = new ImageController();
   
        // Untuk driver, gunakan driver_id dari session
        if (session()->has('is_driver') && session()->get('is_driver')) {
            $driver_id = session()->get('driver_id');

            if (!$driver_id) {
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

            $driver_id = Auth::id();

            if (!$driver_id) {
                \Log::error('Driver ID null saat membuat destinasi', [
                    'user' => Auth::user(),
                    'is_logged_in' => Auth::check()
                ]);

                return redirect()->route('driver.add-destination')
                    ->with('error', 'Gagal mendapatkan ID pengguna. Silahkan login ulang.')
                    ->withInput();
            }
        }

        $destinasi = new Destinasi();
        $destinasi->driver_id = $driver_id;
        $destinasi->kode_destinasi = 'DST-' . strtoupper(Str::random(8));
        $destinasi->travel_name = $request->travel_name;
        $destinasi->start_date = $request->start_date;
        $destinasi->end_date = $request->end_date;
        $destinasi->check_point = $request->check_point;
        $destinasi->end_point = $request->end_point;
        $destinasi->vehicle_type = $request->vehicle_type;
        $destinasi->plate_number = $request->plate_number;
        $destinasi->number_of_seats = $request->number_of_seats;
        $destinasi->price = $request->price;
        $destinasi->link_wa_group = $request->link_wa_group;
        $destinasi->deskripsi = $request->deskripsi;
        $destinasi->foto = $image->uploadImage($request->file('foto'));
        $destinasi->status = Destinasi::STATUS_ORDERABLE;
        $destinasi->save();

        return redirect()->route('driver.destination-list')->with('success', 'Berhasil menambahkan destinasi.');
    }

    // View Destinasi
    public function show(Request $request)
    {
        $id = $request->query('id');
        $destinasi = Destinasi::findOrFail($id);
        return view('app.driver.detail-destination', compact('destinasi'));
    }

    // Search Destinasi
    public function search(Request $request)
    {
        // Get unique locations for dropdowns
        $destinations = Destinasi::select('check_point', 'end_point')
            ->distinct()
            ->get()
            ->map(function ($item) {
                return [
                    'check_point' => $item->check_point,
                    'end_point' => $item->end_point
                ];
            });

        $uniqueLocations = collect();
        foreach ($destinations as $destination) {
            $uniqueLocations->push($destination['check_point']);
            $uniqueLocations->push($destination['end_point']);
        }
        $uniqueLocations = $uniqueLocations->unique()->values();

        // Build search query
        $query = Destinasi::query();

        // Filter untuk hanya menampilkan destinasi dengan tanggal hari ini dan seterusnya
        $query->whereDate('start_date', '>=', now()->startOfDay());

        if ($request->filled('origin')) {
            $query->where('check_point', $request->origin);
        }

        if ($request->filled('destination')) {
            $query->where('end_point', $request->destination);
        }

        if ($request->filled('date')) {
            $query->whereDate('start_date', $request->date);
        }

        // Urutkan berdasarkan tanggal terdekat
        $query->orderBy('start_date', 'asc');

        $destinasi = $query->get();

        // Return the appropriate view based on user's authentication status
        if (Auth::check()) {
            return view('app.user.destination-list', compact('destinasi', 'uniqueLocations'));
        } else {
            return view('app.user.destination-list', compact('destinasi', 'uniqueLocations'));
        }
    }

    public function searchUser(Request $request)
    {
        return $this->search($request);
    }

    public function searchUserNotLogin(Request $request)
    {
        return $this->search($request);
    }

    // Update Destinasi (re-added)
    public function update(Request $request)
    {
        $id = $request->query('id');
        $destinasi = \App\Models\Destinasi::findOrFail($id);

        $destinations = Destinasi::select('check_point', 'end_point')
            ->distinct()
            ->get();

        $checkPoints = $destinations->pluck('check_point')->unique()->values();
        $endPoints = $destinations->pluck('end_point')->unique()->values();

        return view('app.driver.update-destination', compact('destinasi', 'checkPoints', 'endPoints'));
    }

    public function updatePost(Request $request)
    {
        $id = $request->query('id');
        $destinasi = Destinasi::findOrFail($id);

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'travel_name' => [
                'required',
                'min:3',
                'max:20',
                'regex:/^(?!^\d+$).*$/' // Not just numbers
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'check_point' => [
                'required',
                'min:3', 
                'max:20',
                'regex:/^(?!^\d+$).*$/' // Not just numbers
            ],
            'end_point' => [
                'required',
                'min:3',
                'max:20', 
                'regex:/^(?!^\d+$).*$/' // Not just numbers
            ],
            'vehicle_type' => [
                'required',
                'min:3',
                'max:20',
                'regex:/^(?!^\d+$).*$/' // Not just numbers
            ],
            'plate_number' => [
                'required',
                'min:3',
                'max:20'
            ],
            'number_of_seats' => 'required|numeric',
            'price' => 'required|numeric',
            'deskripsi' => 'required|min:3|max:20',
        ], [
            'travel_name.required' => 'Nama travel harus diisi',
            'travel_name.min' => 'Nama travel minimal 3 karakter',
            'travel_name.max' => 'Nama travel maksimal 20 karakter',
            'travel_name.regex' => 'Nama travel tidak boleh hanya angka',
            'start_date.required' => 'Tanggal keberangkatan harus diisi',
            'start_date.date' => 'Format tanggal keberangkatan tidak valid',
            'end_date.required' => 'Tanggal kedatangan harus diisi',
            'end_date.date' => 'Format tanggal kedatangan tidak valid',
            'check_point.required' => 'Titik keberangkatan harus diisi',
            'check_point.min' => 'Titik keberangkatan minimal 3 karakter',
            'check_point.max' => 'Titik keberangkatan maksimal 20 karakter',
            'check_point.regex' => 'Titik keberangkatan tidak boleh hanya angka',
            'end_point.required' => 'Titik kedatangan harus diisi',
            'end_point.min' => 'Titik kedatangan minimal 3 karakter',
            'end_point.max' => 'Titik kedatangan maksimal 20 karakter',
            'end_point.regex' => 'Titik kedatangan tidak boleh hanya angka',
            'vehicle_type.required' => 'Jenis kendaraan harus diisi',
            'vehicle_type.min' => 'Jenis kendaraan minimal 3 karakter',
            'vehicle_type.max' => 'Jenis kendaraan maksimal 20 karakter',
            'vehicle_type.regex' => 'Jenis kendaraan tidak boleh hanya angka',
            'plate_number.required' => 'Nomor plat harus diisi',
            'plate_number.min' => 'Nomor plat minimal 3 karakter',
            'plate_number.max' => 'Nomor plat maksimal 20 karakter',
            'number_of_seats.required' => 'Jumlah kursi harus diisi',
            'number_of_seats.numeric' => 'Jumlah kursi harus berupa angka',
            'price.required' => 'Harga harus diisi',
            'price.numeric' => 'Harga harus berupa angka',
            'deskripsi.required' => 'Deskripsi harus diisi',
            'deskripsi.min' => 'Deskripsi minimal 3 karakter',
            'deskripsi.max' => 'Deskripsi maksimal 20 karakter'
        ]);

        if ($validator->fails()) {
            return redirect()->route('driver.update-destination', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }

        // Update the fields
        $destinasi->travel_name = $request->travel_name;
        $destinasi->start_date = $request->start_date;
        $destinasi->end_date = $request->end_date;
        $destinasi->check_point = $request->check_point;
        $destinasi->end_point = $request->end_point;
        $destinasi->vehicle_type = $request->vehicle_type;
        $destinasi->plate_number = $request->plate_number;
        $destinasi->number_of_seats = $request->number_of_seats;
        $destinasi->price = $request->price;
        $destinasi->deskripsi = $request->deskripsi;

        // Handle optional photo update
        if ($request->hasFile('foto')) {
            $image = new ImageController();
            // Remove old image if it exists
            if ($destinasi->foto) {
                $oldImagePath = public_path($destinasi->foto);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
            $destinasi->foto = $image->uploadImage($request->file('foto'));
        }

        $destinasi->save();

        return redirect()->route('driver.destination-list')->with('success', 'Berhasil mengupdate destinasi.');
    }

    // Delete Destinasi

    public function delete(Request $request)
    {
        $id = $request->query('id');
        $destinasi = Destinasi::findOrFail($id);
        $destinasi->delete();
        return redirect()->route('driver.destination-list')->with('success', 'Berhasil menghapus destinasi.');
    }

    // User Destination List
    public function userDestinationList()
    {
        $destinations = Destinasi::select('check_point', 'end_point')
            ->distinct()
            ->get()
            ->map(function ($item) {
                return [
                    'check_point' => $item->check_point,
                    'end_point' => $item->end_point
                ];
            });

        $uniqueLocations = collect();
        foreach ($destinations as $destination) {
            $uniqueLocations->push($destination['check_point']);
            $uniqueLocations->push($destination['end_point']);
        }
        $uniqueLocations = $uniqueLocations->unique()->values();

        // Filter untuk hanya menampilkan destinasi dengan tanggal hari ini dan seterusnya
        $destinasi = Destinasi::whereDate('start_date', '>=', now()->startOfDay())
            ->orderBy('start_date', 'asc')
            ->get();

        return view('app.user.destination-list', compact('destinasi', 'uniqueLocations'));
    }

    // Destination list for not login
    public function destinationListNotLogin()
    {
        $destinations = Destinasi::select('check_point', 'end_point')
            ->distinct()
            ->get()
            ->map(function ($item) {
                return [
                    'check_point' => $item->check_point,
                    'end_point' => $item->end_point
                ];
            });

        $uniqueLocations = collect();
        foreach ($destinations as $destination) {
            $uniqueLocations->push($destination['check_point']);
            $uniqueLocations->push($destination['end_point']);
        }
        $uniqueLocations = $uniqueLocations->unique()->values();

        // Filter untuk hanya menampilkan destinasi dengan tanggal hari ini dan seterusnya
        $destinasi = Destinasi::whereDate('start_date', '>=', now()->startOfDay())
            ->orderBy('start_date', 'asc')
            ->get();

        return view('app.user.destination-list', compact('destinasi', 'uniqueLocations'));
    }

    public function detailDestination($id)
    {
        $destinasi = Destinasi::findOrFail($id);
        return view('app.user.detail-destination', compact('destinasi'));
    }

    public function passengerDetails($id)
    {
        $destinasi = Destinasi::findOrFail($id);
        return view('app.user.passenger-details', compact('destinasi'));
    }


    // Order List
    public function orderList()
    {
        $orders = Order::whereHas('destinasi', function($query) {
            $query->where('driver_id', session()->get('driver_id'));
        })->get();
        return view('app.driver.order-list', compact('orders'));
    }

    // Search order by customer name for driver
    public function searchOrder(Request $request)
    {
        $search = $request->input('search');

        $orders = Order::whereHas('destinasi', function($query) {
            $query->where('driver_id', session()->get('driver_id'));
        })->whereHas('user', function($query) use ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        })->get();

        return view('app.driver.order-list', compact('orders'));
    }
}
