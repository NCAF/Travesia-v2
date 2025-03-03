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

        if (!isset($request->length) || !isset($request->start)) {
            $destinasi = $destinasi->get();
        } else {
            $destinasi = $destinasi->skip($start)->take($length)->get();
        }

        return response()->json([
            'error' => false,
            'message' => 'Berhasil mengambil data.',
            'data' => $destinasi,
            'draw' => $request->draw,
            'recordsTotal' => $total_data,
            'recordsFiltered' => $total_data,
        ], 200);
    }

    public function recent(Request $request)
    {
        $destinasi = Destinasi::select('*');

        $destinasi = $destinasi->orderBy('id', 'DESC')->take(5)->get();

        return response()->json([
            'error' => false,
            'message' => 'Berhasil mengambil data.',
            'data' => $destinasi,
        ], 200);
    }

    public function all(Request $request)
    {
        $destinasi = Destinasi::select(['*',
            DB::raw('
                (jumlah_kursi - COALESCE((SELECT SUM(orders.jumlah_kursi) FROM orders WHERE orders.destinasi_id = destinasi.id AND orders.status != "canceled"), 0)) AS kursi_tersisa,
                (jumlah_bagasi - COALESCE((SELECT SUM(orders.jumlah_bagasi) FROM orders WHERE orders.destinasi_id = destinasi.id AND orders.status != "canceled"), 0)) AS bagasi_tersisa
            ')
        ]);

        $dari = !empty($request->get('dari')) ? $request->get('dari') : '';
        $tujuan = !empty($request->get('tujuan')) ? $request->get('tujuan') : '';
        $tanggal = !empty($request->get('tanggal')) ? $request->get('tanggal') : '';

        if (!empty($dari)) {
            $destinasi = $destinasi->where('destinasi_awal', 'LIKE', "%$dari%");
        }

        if (!empty($tujuan)) {
            $destinasi = $destinasi->where('destinasi_akhir', 'LIKE', "%$tujuan%");
        }

        if (!empty($tanggal)) {
            $destinasi = $destinasi->whereDate('hari_berangkat', $tanggal);
        }

        $total = $destinasi->count();

        $length = intval(isset($request->length) ? $request->length : 10);
        $skip = intval(isset($request->page) ? ($request->page*$length) : 0);

        $destinasi = $destinasi->orderBy('hari_berangkat', 'DESC')
                                ->skip($skip)->take($length)->get();

        return response()->json([
            'error' => false,
            'message' => 'Berhasil mengambil data.',
            'data' => $destinasi,
            'length' => $length,
            'skip' => $skip,
            'end' => ($request->page*$length)+$length >= $total ? true : false,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

        $base64Data = $request->input('foto');

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => Str::ucfirst($validator->errors()->first()),
                'data' => null
            ]);
        }

        $hariBerangkat = Carbon::parse($request->hari_berangkat);
        $besokTanggal = Carbon::now()->addDay();

        if ($hariBerangkat <= $besokTanggal) {
            return response()->json([
                'error' => true,
                'message' => 'Hari Keberangkatan setidaknya 1 hari setelah hari ini.',
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
        $uploadPath = public_path('uploads/destinasi');
        File::makeDirectory($uploadPath, 0755, true, true);
        $filename = $this->generateRandomString(33).time() . ".".$app_type;
        $filePath = $uploadPath . '/' . $filename;
        file_put_contents($filePath, $fotoData);

        $destinasi = Destinasi::create([
            'user_id' => auth()->id(),
            'kode_destinasi' => 'DTNS-'.auth()->id().'-'.time(),
            'destinasi_awal' => $request->destinasi_awal,
            'destinasi_akhir' => $request->destinasi_akhir,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'no_plat' => $request->no_plat,
            'hari_berangkat' => $request->hari_berangkat,
            'jumlah_kursi' => $request->jumlah_kursi,
            'jumlah_bagasi' => $request->jumlah_bagasi,
            'foto' => '/uploads/destinasi/'.$filename,
            'deskripsi' => $request->deskripsi,
            'harga_kursi' => $request->harga_kursi,
            'harga_bagasi' => $request->harga_bagasi,
            'status' => 'orderable',
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Destinasi berhasil dibuat.',
            'data' => null
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $kode)
    {
        $destinasi = Destinasi::select(['*',
            DB::raw('
                (jumlah_kursi - COALESCE((SELECT SUM(orders.jumlah_kursi) FROM orders WHERE orders.destinasi_id = destinasi.id AND orders.status != "canceled"), 0)) AS kursi_tersisa,
                (jumlah_bagasi - COALESCE((SELECT SUM(orders.jumlah_bagasi) FROM orders WHERE orders.destinasi_id = destinasi.id AND orders.status != "canceled"), 0)) AS bagasi_tersisa
            ')
        ])->where('kode_destinasi', $kode)->first();

        if (!$destinasi) {
            return response()->json([
                'error' => true,
                'message' => 'Destinasi tidak ditemukan.',
                'data' => null
            ], 200);
        }

        return response()->json([
            'error' => false,
            'message' => 'Berhasil mengambil data.',
            'data' => $destinasi
        ], 200);
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
            return response()->json([
                'error' => true,
                'message' => Str::ucfirst($validator->errors()->first()),
                'data' => null
            ]);
        }

        $destinasi = Destinasi::find($id);

        if (!$destinasi) {
            return response()->json([
                'error' => true,
                'message' => 'Destinasi tidak ditemukan.',
                'data' => null
            ], 200);
        }

        $hariBerangkat = Carbon::parse($request->hari_berangkat);
        $besokTanggal = Carbon::now()->addDay();

        if ($hariBerangkat <= $besokTanggal) {
            return response()->json([
                'error' => true,
                'message' => 'Hari Keberangkatan setidaknya 1 hari setelah hari ini.',
                'data' => null
            ]);
        }

        $foto = $destinasi->foto;
        $base64Data = $request->input('foto');

        if (!empty($base64Data) && $base64Data != "null") {
            $file_type = explode(';base64,', $base64Data);
            $file_type = explode('data:', $file_type[0]);
            $file_type = explode('/', $file_type[1]);
            $data_type = $file_type[0];
            $app_type = $file_type[1];
            $file_convert = str_replace("data:$data_type/" . $app_type . ';base64,', '', $base64Data);
            $file_convert = str_replace(' ', '+', $file_convert);

            $fotoData = base64_decode($file_convert);

            // Simpan file sementara
            $uploadPath = public_path('uploads/destinasi');
            File::makeDirectory($uploadPath, 0755, true, true);
            $filename = $this->generateRandomString(33).time() . ".".$app_type;
            $filePath = $uploadPath . '/' . $filename;
            file_put_contents($filePath, $fotoData);

            $foto = '/uploads/destinasi/'.$filename;
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

        return response()->json([
            'error' => false,
            'message' => 'Destinasi berhasil diubah.',
            'data' => null
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $destinasi = Destinasi::find($id);

        if (!$destinasi) {
            return response()->json([
                'error' => true,
                'message' => 'Destinasi tidak ditemukan.',
                'data' => null
            ], 200);
        }

        File::delete(public_path($destinasi->sampul));

        $destinasi->delete();

        return response()->json([
            'error' => false,
            'message' => 'Destinasi berhasil dihapus.',
            'data' => null
        ]);
    }

    public function traveling(string $id)
    {
        $destinasi = Destinasi::find($id);

        if (!$destinasi) {
            return response()->json([
                'error' => true,
                'message' => 'Destinasi tidak ditemukan.',
                'data' => null
            ], 200);
        }

        $destinasi->update([
            'status' => 'traveling'
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Status destinasi berhasil diubah.',
            'data' => null
        ]);
    }

    public function arrived(string $id)
    {
        $destinasi = Destinasi::find($id);

        if (!$destinasi) {
            return response()->json([
                'error' => true,
                'message' => 'Destinasi tidak ditemukan.',
                'data' => null
            ], 200);
        }

        $destinasi->update([
            'status' => 'arrived'
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Status destinasi berhasil diubah.',
            'data' => null
        ]);
    }

    public function order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'kursi' => 'required|numeric|min:1',
            'bagasi' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => Str::ucfirst($validator->errors()->first()),
                'data' => null
            ]);
        }

        $destinasi = Destinasi::select(['*',
            DB::raw('
                (jumlah_kursi - COALESCE((SELECT SUM(orders.jumlah_kursi) FROM orders WHERE orders.destinasi_id = destinasi.id AND orders.status != "canceled"), 0)) AS kursi_tersisa,
                (jumlah_bagasi - COALESCE((SELECT SUM(orders.jumlah_bagasi) FROM orders WHERE orders.destinasi_id = destinasi.id AND orders.status != "canceled"), 0)) AS bagasi_tersisa
            ')
        ])->where('id', $request->id)->first();

        if (!$destinasi) {
            return response()->json([
                'error' => true,
                'message' => 'Destinasi tidak ditemukan.',
                'data' => null
            ], 200);
        }

        if ($destinasi->status != 'orderable') {
            return response()->json([
                'error' => true,
                'message' => 'Destinasi ini sudah melakukan close order.',
                'data' => null
            ], 200);
        }

        if ($destinasi->kursi_tersisa < $request->kursi || $destinasi->bagasi_tersisa < $request->bagasi) {
            return response()->json([
                'error' => true,
                'message' => 'Jumlah kursi atau bagasi yang tersisa tidak mencukupi.',
                'data' => null
            ], 200);
        }

        $user = Auth::user();

        $order_id = rand();

        $order = Order::create([
            'user_id' => $user->id,
            'destinasi_id' => $request->id,
            'jumlah_kursi' => $request->kursi,
            'jumlah_bagasi' => $request->bagasi,
            'harga_kursi' => $destinasi->harga_kursi,
            'harga_bagasi' => $destinasi->harga_bagasi,
            'subtotal' => (
                            ($request->kursi*$destinasi->harga_kursi)+
                            ($request->bagasi*$destinasi->harga_bagasi)
                        ),
            'status' => 'order',
            'order_id' => $order_id
        ]);

        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is3ds');

        $params = array(
            'transaction_details' => array(
                'order_id' => $order_id,
                'gross_amount' => (
                    ($request->kursi*$destinasi->harga_kursi)+
                    ($request->bagasi*$destinasi->harga_bagasi)
                ),
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

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $order->token = $snapToken;
        $order->save();

        return response()->json([
            'error' => false,
            'message' => 'Checkout berhasil.',
            'data' => null
        ]);
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
