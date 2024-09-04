<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class ChatController extends Controller
{


    // Fetch the last 10 messages
    public function chat($user_id = null)
    {
        $auth_id = auth()->id();
        $users = User::where('id', '!=', $auth_id)->get();
        $firstUser = null;
        $messages = null;
        if ($user_id) {
            $firstUser = User::where('id', $user_id)->first();

            $messages = Message::where(function ($query) use ($firstUser, $auth_id) {
                $query->where('sender_id', $firstUser->id)
                ->where('receiver_id', $auth_id);
            })->orWhere(function ($query) use ($firstUser, $auth_id) {
                $query->where('sender_id', $auth_id)
                ->where('receiver_id', $firstUser->id);
            })->latest()->take(10)->get()->reverse();
            
            // $messages = Message::orWhere('sender_id', $firstUser->id)->orWhere('receiver_id', $firstUser->id)->latest()->take(10)->get()->reverse();
        }

        return view('chat', compact('users', 'firstUser', 'messages'));
    }


    public function fetchMessages()
    {
        $messages = Message::with('user')->latest()->take(10)->get()->reverse();
        return response()->json($messages);
    }

    // Store a new message
    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'sender_id' => Auth::id(),
            'message' => $request->message,
            'receiver_id' => $request->receiver_id,
        ]);

        // Broadcast the message to others using an event (optional)
        // broadcast(new \App\Events\MessageSent($message))->toOthers();

        event(new MessageSent($message));

        return response()->json($message);
    }
}