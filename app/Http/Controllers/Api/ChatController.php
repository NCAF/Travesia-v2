<?php

namespace App\Http\Controllers\Api;

use App\Models\Chat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function seller(Request $request)
    {
        $chat = Chat::select(['*', 'chats.status', 'chats.id'])->join('destinasi', 'destinasi.id', 'chats.destinasi_id');

        $user = Auth::user();
        $chat = $chat->where('destinasi.user_id', '=', $user->id);

        $search = isset($request->search) ? $request->search : '';
        if (!empty($search)) {
            $chat->where(function ($query) use ($search) {
                $query->Where('nama_channel', 'like', '%'.$search.'%')
                        ->orWhere('chats.status', 'like', '%'.$search.'%');
            });
        }
        $chat = $chat->get();

        return response()->json([
            'error' => false,
            'message' => 'Berhasil mengambil data.',
            'data' => $chat
        ], 200);
    }

    public function user(Request $request)
    {
        $chat = Chat::select(['*', 'chats.status', 'chats.id'])
                        ->join('destinasi', 'destinasi.id', 'chats.destinasi_id');

        $user = Auth::user();
        $chat = $chat->whereIn('chats.destinasi_id', function($query) use ($user) {
            $query->select('destinasi_id')
                ->from('orders')
                ->where('user_id', $user->id);
        });

        $search = isset($request->search) ? $request->search : '';
        if (!empty($search)) {
            $chat->where(function ($query) use ($search) {
                $query->Where('nama_channel', 'like', '%'.$search.'%')
                        ->orWhere('chats.status', 'like', '%'.$search.'%');
            });
        }
        $chat = $chat->get();

        return response()->json([
            'error' => false,
            'message' => 'Berhasil mengambil data.',
            'data' => $chat
        ], 200);
    }
}
