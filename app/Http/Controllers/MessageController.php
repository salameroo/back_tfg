<?php

// app/Http/Controllers/MessageController.php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $messages = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with('sender', 'receiver')
            ->get();

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);
        $user = Auth::guard('sanctum')->user();
        $sender = $request->user();
        $receiver = $user::find($request->receiver_id);

        if (!$sender->isFollowing($receiver) || !$receiver->isFollowing($sender)) {
            return response()->json(['message' => 'You can only message users you follow and who follow you back.'], 403);
        }

        $message = Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return response()->json($message, 201);
    }
}
