<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use App\Http\Requests\StoreChatRequest;
use App\Http\Requests\UpdateChatRequest;
use App\Models\Group;
use App\Models\User;

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
        $users = User::where('id', '!=', auth()->id())->get();
        return response()->json(['chats' => $chats, 'users' => $users]);
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
    public function CreateGroup(Request $request)
    {
        $chat = Chat::create([
            'name' => $request->name,
            'type' => 'public',
            'lastMessage' => ''
        ]);
        if ($request->user) {
            $chat->user()->attach($request->user);
        }
        return response()->json(['data' => $chat->load(['user', 'user.media']), 'message' => "create group successfully"]);
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
