<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Http\Requests\StoreChatRequest;
use App\Http\Requests\UpdateChatRequest;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chats = Chat::query()->where('user_id', auth()->id())->with(['user', 'coach.media', 'coach'])->get();
        return response()->json($chats);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChatRequest $request)
    {
        $check = Chat::query()->where('coach_id', $request->id)->where('user_id', auth()->id())->first();

        if ($check) {
            $chat = $check;
        } else {
            $chat = Chat::create([
                'user_id' => auth()->id(),
                'coach_id' => $request->id,
                'lastMessage' => ''
            ]);
        }
        return response()->json($chat);
    }

    /**
     * Display the specified resource.
     */
    public function show(Chat $chat)
    {
        return response()->json($chat->load(['user', 'coach', 'coach.media']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChatRequest $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chat $chat)
    {
        //
    }
}
