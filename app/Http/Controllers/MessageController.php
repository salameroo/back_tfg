<?php

// app/Http/Controllers/MessageController.php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
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

    public function getConversations()
    {
        $userId = Auth::id();

        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->get()
            ->groupBy(function ($message) use ($userId) {
                return $message->sender_id == $userId ? $message->receiver_id : $message->sender_id;
            });

        $users = $conversations->map(function ($messages, $userId) {
            return User::find($userId);
        });

        // Agregar registro de depuración
        \Log::info('Conversations:', $users->toArray());

        return response()->json($users->values());
    }

    public function getMessages($userId)
    {
        $authId = Auth::id();

        $messages = Message::where(function ($query) use ($authId, $userId) {
            $query->where('sender_id', $authId)
                ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($authId, $userId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', $authId);
        })->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->reverse()
            ->values();

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
