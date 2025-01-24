<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Chat;
use App\Models\Group;
use App\Models\UserService;
use Carbon\Carbon;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $meesage = '';
        $check = UserService::where('user_id', auth()->id())->with('service')->first();
        if ($check) {
            $date1 = Carbon::parse($check->created_at);
            $date2 = Carbon::now();
            $differenceInDays = $date1->diffInDays($date2);
            if (intval($differenceInDays) > ($check->service->duration * 7)) {
                $meesage = 'Your service period has expired. Please renew your subscription to one of the services to be able to communicate with the trainers.😊😊';
            }
        } else {
            $meesage = 'Please subscribe to one of the services to be able to communicate😊😊';
        }
        $messages = Message::query()->whereHas('group', function ($q) use ($id) {
            $q->where('chat_id', $id);
        })->with('group.user')->get();
        return response()->json(['data' => $messages, 'message' => $meesage]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function sendMessage(StoreMessageRequest $request)
    {
        $group = Group::where('user_id', auth()->id())->where('chat_id', $request->chat_id)->get('id');
        $store = Message::create([
            'text' => $request->text,
            'group_id' => $group[0]->id,
            'isCoach' => 0,
            'isSeen' => 0
        ]);
        Chat::where('id', $request->chat_id)->update([
            'lastMessage' => $request->text,
        ]);
        return response()->json([
            'message' => $store->load(['group.user']),
            'chat' => $request->text
        ]);
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
