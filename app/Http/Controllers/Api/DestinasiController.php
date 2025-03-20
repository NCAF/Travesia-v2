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

class DestinasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $destinasi = Destinasi::select('*', DB::raw("CASE WHEN NOW() >= DATE_SUB(hari_berangkat, INTERVAL 1 DAY) THEN 1 ELSE 0 END as traveling, NOW() as today, DATE_SUB(hari_berangkat, INTERVAL 1 DAY) as berangkat"));
        $destinasi->where('user_id', auth()->id());

        $search = isset($request->search['value']) ? $request->search['value'] : '';
        if (!empty($search)) {
            $destinasi->where(function ($query) use ($search) {
                $query->where('destinasi_awal', 'like', '%'.$search.'%')
                    ->orWhere('destinasi_akhir', 'like', '%'.$search.'%')
                    ->orWhere('status', 'like', '%'.$search.'%')
                    ->orWhere('kode_destinasi', 'like', '%'.$search.'%');
            });
        }

        $total_data = $destinasi->count();
        $length = intval(isset($request->length) ? $request->length : 0);
        $start = intval(isset($request->start) ? $request->start : 0);

        if (isset($request->length) && isset($request->start)) {
            $destinasi = $destinasi->skip($start)->take($length)->get();
        } else {
            $destinasi = $destinasi->get();
        }

        return $this->successResponse('Berhasil mengambil data.', $destinasi, [
            'draw' => $request->draw,
            'recordsTotal' => $total_data,
            'recordsFiltered' => $total_data,
        ]);
    }

    public function recent(Request $request)
    {
        $destinasi = Destinasi::select('*')
                              ->orderBy('id', 'DESC')
                              ->take(5)
                              ->get();

        return $this->successResponse('Berhasil mengambil data.', $destinasi);
    }

    public function all(Request $request)
    {
        $destinasi = Destinasi::select(['*',
            DB::raw('
                (jumlah_kursi - COALESCE((SELECT SUM(orders.jumlah_kursi) FROM orders WHERE orders.destinasi_id = destinasi.id AND orders.status != "canceled"), 0)) AS kursi_tersisa,
                (jumlah_bagasi - COALESCE((SELECT SUM(orders.jumlah_bagasi) FROM orders WHERE orders.destinasi_id = destinasi.id AND orders.status != "canceled"), 0)) AS bagasi_tersisa
            ')
        ]);

        $this->applyFilters($destinasi, $request);
        
        $total = $destinasi->count();
        $length = intval(isset($request->length) ? $request->length : 10);
        $skip = intval(isset($request->page) ? ($request->page*$length) : 0);

        $destinasi = $destinasi->orderBy('hari_berangkat', 'DESC')
                                ->skip($skip)
                                ->take($length)
                                ->get();

        return $this->successResponse('Berhasil mengambil data.', $destinasi, [
            'length' => $length,
            'skip' => $skip,
            'end' => ($request->page*$length)+$length >= $total,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $this->validateDestinasi($request);
        if ($validator->fails()) {
            return $this->errorResponse(Str::ucfirst($validator->errors()->first()));
        }

        if (!$this->validateDepartureDate($request->hari_berangkat)) {
            return $this->errorResponse('Hari Keberangkatan setidaknya 1 hari setelah hari ini.');
        }

        $imagePath = $this->processImage($request->input('foto'));
        if (!$imagePath) {
            return $this->errorResponse('Gagal menyimpan gambar.');
        }

        Destinasi::create([
            'user_id' => auth()->id(),
            'kode_destinasi' => 'DTNS-'.auth()->id().'-'.time(),
            'destinasi_awal' => $request->destinasi_awal,
            'destinasi_akhir' => $request->destinasi_akhir,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'no_plat' => $request->no_plat,
            'hari_berangkat' => $request->hari_berangkat,
            'jumlah_kursi' => $request->jumlah_kursi,
            'jumlah_bagasi' => $request->jumlah_bagasi,
            'foto' => $imagePath,
            'deskripsi' => $request->deskripsi,
            'harga_kursi' => $request->harga_kursi,
            'harga_bagasi' => $request->harga_bagasi,
            'status' => 'orderable',
        ]);

        return $this->successResponse('Destinasi berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $kode)
    {
        $destinasi = $this->getDestinasiWithAvailability()
                         ->where('kode_destinasi', $kode)
                         ->first();

        if (!$destinasi) {
            return $this->errorResponse('Destinasi tidak ditemukan.');
        }

        return $this->successResponse('Berhasil mengambil data.', $destinasi);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'destinasi_awal' => 'required',
            'destinasi_akhir' => 'required',
            'jenis_kendaraan' => 'required',
            'no_plat' => 'required',
            'hari_berangkat' => 'required',
            'jumlah_kursi' => 'required',
            'jumlah_bagasi' => 'required',
            'deskripsi' => 'required',
            'harga_kursi' => 'required',
            'harga_bagasi' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(Str::ucfirst($validator->errors()->first()));
        }

        $destinasi = Destinasi::find($id);
        if (!$destinasi) {
            return $this->errorResponse('Destinasi tidak ditemukan.');
        }

        if (!$this->validateDepartureDate($request->hari_berangkat)) {
            return $this->errorResponse('Hari Keberangkatan setidaknya 1 hari setelah hari ini.');
        }

        $foto = $destinasi->foto;
        $base64Data = $request->input('foto');

        if (!empty($base64Data) && $base64Data != "null") {
            $foto = $this->processImage($base64Data);
            File::delete(public_path($destinasi->sampul));
        }

        $destinasi->update([
            'user_id' => auth()->id(),
            'destinasi_awal' => $request->destinasi_awal,
            'destinasi_akhir' => $request->destinasi_akhir,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'no_plat' => $request->no_plat,
            'hari_berangkat' => $request->hari_berangkat,
            'jumlah_kursi' => $request->jumlah_kursi,
            'jumlah_bagasi' => $request->jumlah_bagasi,
            'foto' => $foto,
            'deskripsi' => $request->deskripsi,
            'harga_kursi' => $request->harga_kursi,
            'harga_bagasi' => $request->harga_bagasi,
        ]);

        return $this->successResponse('Destinasi berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $destinasi = Destinasi::find($id);
        if (!$destinasi) {
            return $this->errorResponse('Destinasi tidak ditemukan.');
        }

        File::delete(public_path($destinasi->sampul));
        $destinasi->delete();

        return $this->successResponse('Destinasi berhasil dihapus.');
    }

    public function traveling(string $id)
    {
        return $this->updateStatus($id, 'traveling');
    }

    public function arrived(string $id)
    {
        return $this->updateStatus($id, 'arrived');
    }

    public function order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'kursi' => 'required|numeric|min:1',
            'bagasi' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(Str::ucfirst($validator->errors()->first()));
        }

        $destinasi = $this->getDestinasiWithAvailability()
                         ->where('id', $request->id)
                         ->first();

        if (!$destinasi) {
            return $this->errorResponse('Destinasi tidak ditemukan.');
        }

        if ($destinasi->status != 'orderable') {
            return $this->errorResponse('Destinasi ini sudah melakukan close order.');
        }

        if ($destinasi->kursi_tersisa < $request->kursi || $destinasi->bagasi_tersisa < $request->bagasi) {
            return $this->errorResponse('Jumlah kursi atau bagasi yang tersisa tidak mencukupi.');
        }

        $user = Auth::user();
        $order_id = rand();
        $subtotal = ($request->kursi * $destinasi->harga_kursi) + 
                    ($request->bagasi * $destinasi->harga_bagasi);

        $order = Order::create([
            'user_id' => $user->id,
            'destinasi_id' => $request->id,
            'jumlah_kursi' => $request->kursi,
            'jumlah_bagasi' => $request->bagasi,
            'harga_kursi' => $destinasi->harga_kursi,
            'harga_bagasi' => $destinasi->harga_bagasi,
            'subtotal' => $subtotal,
            'status' => 'order',
            'order_id' => $order_id
        ]);

        $snapToken = $this->processMidtransPayment($order_id, $subtotal, $user);
        
        $order->token = $snapToken;
        $order->save();

        return $this->successResponse('Checkout berhasil.');
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    private function validateDestinasi(Request $request) {
        return Validator::make($request->all(), [
            'destinasi_awal' => 'required',
            'destinasi_akhir' => 'required',
            'jenis_kendaraan' => 'required',
            'no_plat' => 'required',
            'hari_berangkat' => 'required',
            'jumlah_kursi' => 'required',
            'jumlah_bagasi' => 'required',
            'foto' => 'required',
            'deskripsi' => 'required',
            'harga_kursi' => 'required',
            'harga_bagasi' => 'required',
        ]);
    }

    private function validateDepartureDate($departureDate) {
        $hariBerangkat = Carbon::parse($departureDate);
        $besokTanggal = Carbon::now()->addDay();
        return $hariBerangkat > $besokTanggal;
    }

    private function processImage($base64Data) {
        $file_type = explode(';base64,', $base64Data);
        $file_type = explode('data:', $file_type[0]);
        $file_type = explode('/', $file_type[1]);
        $data_type = $file_type[0];
        $app_type = $file_type[1];
        $file_convert = str_replace("data:$data_type/" . $app_type . ';base64,', '', $base64Data);
        $file_convert = str_replace(' ', '+', $file_convert);

        $fotoData = base64_decode($file_convert);

        $uploadPath = public_path('uploads/destinasi');
        File::makeDirectory($uploadPath, 0755, true, true);
        
        $filename = $this->generateRandomString(33) . time() . "." . $app_type;
        $filePath = $uploadPath . '/' . $filename;
        file_put_contents($filePath, $fotoData);

        return '/uploads/destinasi/' . $filename;
    }

    private function updateStatus($id, $status) {
        $destinasi = Destinasi::find($id);
        if (!$destinasi) {
            return $this->errorResponse('Destinasi tidak ditemukan.');
        }

        $destinasi->update(['status' => $status]);
        return $this->successResponse('Status destinasi berhasil diubah.');
    }

    private function processMidtransPayment($order_id, $amount, $user) {
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is3ds');

        $params = array(
            'transaction_details' => array(
                'order_id' => $order_id,
                'gross_amount' => $amount,
            ),
            'customer_details' => array(
                'first_name' => $user->nama,
                'last_name' => '',
                'email' => $user->email,
                'phone' => $user->telp,
            ),
            'callbacks' => array(
                'finish' => url('/')
            ),
        );

        return \Midtrans\Snap::getSnapToken($params);
    }

    private function successResponse($message, $data = null, $additional = []) {
        $response = [
            'error' => false,
            'message' => $message,
            'data' => $data
        ];
        
        return response()->json(array_merge($response, $additional), 200);
    }

    private function errorResponse($message, $code = 200) {
        return response()->json([
            'error' => true,
            'message' => $message,
            'data' => null
        ], $code);
    }

    private function getDestinasiWithAvailability() {
        return Destinasi::select(['*',
            DB::raw('
                (jumlah_kursi - COALESCE((SELECT SUM(orders.jumlah_kursi) FROM orders WHERE orders.destinasi_id = destinasi.id AND orders.status != "canceled"), 0)) AS kursi_tersisa,
                (jumlah_bagasi - COALESCE((SELECT SUM(orders.jumlah_bagasi) FROM orders WHERE orders.destinasi_id = destinasi.id AND orders.status != "canceled"), 0)) AS bagasi_tersisa
            ')
        ]);
    }

    private function applyFilters($query, $request) {
        $dari = !empty($request->get('dari')) ? $request->get('dari') : '';
        $tujuan = !empty($request->get('tujuan')) ? $request->get('tujuan') : '';
        $tanggal = !empty($request->get('tanggal')) ? $request->get('tanggal') : '';

        if (!empty($dari)) {
            $query->where('destinasi_awal', 'LIKE', "%$dari%");
        }

        if (!empty($tujuan)) {
            $query->where('destinasi_akhir', 'LIKE', "%$tujuan%");
        }

        if (!empty($tanggal)) {
            $query->whereDate('hari_berangkat', $tanggal);
        }
        
        return $query;
    }
}
