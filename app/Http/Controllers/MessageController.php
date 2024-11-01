<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $messages = Message::query()->where('chat_id', $id)->get();
        return response()->json($messages);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function sendMessage(StoreMessageRequest $request)
    {
        $store = Message::create([
            'text' => $request->text,
            'chat_id' => $request->id,
            'user_id' => auth()->id(),
            'isCoach' => 0,
            'isSeen' => 0
        ]);
        return response()->json($store);
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageRequest $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }
}
