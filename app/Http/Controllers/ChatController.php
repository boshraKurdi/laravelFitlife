<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Http\Requests\StoreChatRequest;
use App\Http\Requests\UpdateChatRequest;
use App\Models\Group;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ids = Group::query()->where('user_id', auth()->id())->with(['user', 'user.media'])->get('chat_id');
        $arr = [];
        foreach ($ids as $id) {
            array_push($arr, $id->chat_id);
        }
        $chats = Chat::query()->whereIn('id', $arr)->with(['user'  => function ($q) {
            $q->where('user_id', '!=', auth()->id());
        }, 'user.media'])->get();
        return response()->json($chats);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChatRequest $request)
    {
        $ids = Group::query()->where('user_id', auth()->id())->get('chat_id');
        $arr = [];
        foreach ($ids as $id) {
            array_push($arr, $id->chat_id);
        }
        $count = Group::query()->whereIn('chat_id', $arr)->where('user_id', $request->id)->count();
        if ($count > 0) {
            $check = Chat::query()->whereIn('id', $arr)->with(['user', 'user.media'])->first();
        } else {
            $check = 0;
        }

        if ($check) {
            $chat = $check;
        } else {
            $chat = Chat::create([
                'name' => '',
                'type' => 'private',
                'lastMessage' => ''
            ]);
            if ($request->id) {
                $chat->user()->attach($request->id);
                $chat->user()->attach(auth()->id());
            }
        }

        return response()->json($chat->load(['user', 'user.media']));
    }

    /**
     * Display the specified resource.
     */
    public function show($chat)
    {

        return response()->json(Chat::where('id', $chat)->with(['user' => function ($q) {
            $q->where('user_id', '!=', auth()->id());
        }, 'user.media'])->get());
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
