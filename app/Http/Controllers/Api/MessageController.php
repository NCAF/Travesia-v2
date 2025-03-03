<?php

namespace App\Http\Controllers\Api;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $chat_id)
    {
        $user_id = auth()->id();
        $message = Message::select(['messages.*', 'users.nama', 'users.foto', 'users.role',
                                    DB::raw("IF (users.id = $user_id, 1, 0) as sender")
                                ])
                                ->join('users', 'users.id', '=', 'sender_id')
                                ->where('chat_id', $chat_id)
                                ->orderBy('messages.id', 'ASC')
                                ->get();
        return response()->json([
            'error' => false,
            'message' => 'Berhasil mengambil data.',
            'data' => $message
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $chat_id)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|max:300',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => Str::ucfirst($validator->errors()->first()),
                'data' => null
            ]);
        }

        $chat = Chat::find($chat_id);
        if ($chat->status == 'closed') {
            return response()->json([
                'error' => true,
                'message' => 'Channel telah ditutup. Tidak dapat mengirim pesan!',
                'data' => null
            ]);
        }

        $message = Message::create([
            'chat_id' => $chat_id,
            'sender_id' => auth()->id(),
            'message' => $request->message
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Pesan terkirim.',
            'data' => null
        ]);
    }
}
