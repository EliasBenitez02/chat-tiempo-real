<?php

namespace App\Http\Controllers;

use App\Events\MessagePosted;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $messages = Message::latest()->take(50)->orderBy('id')->get();
        return view('chat', compact('messages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user' => ['nullable', 'string', 'max:100'],
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $message = Message::create([
            'user' => $data['user'] ?? 'Anon',
            'content' => $data['content'],
        ]);

        event(new MessagePosted($message));

        return response()->json(['ok' => true]);
    }
}