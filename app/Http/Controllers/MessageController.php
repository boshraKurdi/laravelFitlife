<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Chat;
use App\Models\Group;
use App\Models\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $meesage = '';
        $messages = [];
        $checks = UserService::where('user_id', auth()->id())->where('status', 'active')->with('service')->get();
        foreach ($checks as $check) {
            if ($check) {
                $date1 = Carbon::parse($check->created_at);
                $date2 = Carbon::now();
                $differenceInDays = $date1->diffInDays($date2);
                if (intval($differenceInDays) > ($check->service->duration * 7)) {
                    $meesage = app()->getLocale() == 'en' ? 'Your service period has expired. Please renew your subscription to one of the services to be able to communicate with the trainers.😊😊' : "انتهت مدة خدمتك. يُرجى تجديد اشتراكك في إحدى الخدمات لتتمكن من التواصل مع المدربين.😊😊";
                    $check->update([
                        'status' => 'finsh'
                    ]);
                }
            }
        }
        if (count($checks)) {

            $meesage =  app()->getLocale() == 'en' ? 'Please subscribe to one of the services to be able to communicate😊😊' : "الرجاء الاشتراك في إحدى الخدمات لتتمكن من التواصل😊😊";
        }
        $checkcount = UserService::where('user_id', auth()->id())->where('status', 'active')->count();
        if ($checkcount) {
            $message = '';
        }
        Message::whereHas('group', function ($q) use ($id) {
            $q->where('chat_id', $id)->where('user_id', '!=', auth()->id());
        })->update([
            'isSeen' =>
            1
        ]);
        $messages = Message::query()->whereHas('group', function ($q) use ($id) {
            $q->where('chat_id', $id);
        })->with('group.user', 'media')->orderBy('messages.created_at')->get();


        return response()->json(['data' => $messages, 'message' => $meesage]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function sendMessage(StoreMessageRequest $request)
    {
        $group = Group::where('user_id', auth()->id())->where('chat_id', $request->chat_id)->get('id');
        $store = Message::create([
            'text' => $request->text ?  $request->text : "",
            'group_id' => $group[0]->id,
            'isCoach' => 0,
            'isSeen' => 0
        ]);
        if ($request->hasFile("media")) {
            $store->addMediaFromRequest("media")->toMediaCollection('messages');
        }
        Chat::where('id', $request->chat_id)->update([
            'lastMessage' => $request->text,
        ]);

        return response()->json([
            'message' => $store->load(['group.user']),
            'chat' => $request->text
        ]);
    }

    public function sendMessageAi(StoreMessageRequest $request)
    {
        try {
            $group = Group::where('user_id', Auth::id())
                ->where('chat_id', $request->chat_id)
                ->firstOrFail();
            $client = new \GuzzleHttp\Client();
            $aiResponse = null;

            if ($request->hasFile('file')) {
                $message = new Message();
                $message->group_id = $group->id;
                $message->isCoach = 0;
                $message->text = '';
                $message->isSeen = 0;
                $message->save();

                // أضف هذا أولاً قبل محاولة قراءة الملف
                $uploadedFile = $request->file('file');

                // طريقة آمنة لقراءة الملف
                $fileContent = $uploadedFile->getContent(); // الطريقة الموصى بها في Laravel 8+

                // أو استخدم هذه الطريقة إذا لم تعمل السابقة
                // $fileContent = file_get_contents($uploadedFile->getRealPath());
                if (!$uploadedFile->isValid()) {
                    return response()->json(['error' => 'File upload failed'], 400);
                }

                if (!file_exists($uploadedFile->getRealPath())) {
                    return response()->json(['error' => 'Temp file not found'], 400);
                }

                $response = $client->post('http://127.0.0.1:8001/analyze_image/', [
                    'multipart' => [
                        [
                            'name' => 'file',
                            'contents' => $fileContent,
                            'filename' => $uploadedFile->getClientOriginalName()
                        ]
                    ]
                ]);
                $data = json_decode($response->getBody()->getContents(), true);
                $aiResponse = $data['fitness_advice'];
            } elseif ($request->has('text')) {
                // إرسال النص إلى خدمة Python
                $response = $client->post("http://127.0.0.1:8001/chat/?user_input=" . $request->input('text'), [
                    'headers' => ['Content-Type' => 'application/json'],
                    // 'body'    => json_encode(['user_input' => $request->input('text')])
                ]);

                $data = json_decode($response->getBody()->getContents(), true);
                $aiResponse = $data['response']; // الرد من API

                // تخزين النص في قاعدة البيانات
                $user = Message::create([
                    'text' => $request->text,
                    'group_id' => $group->id,
                    'isCoach' => 0,
                    'isSeen' => 0
                ]);
            } else {
                return response()->json(['message' => 'لم يتم إرسال أي بيانات صالحة.'], 400);
            }

            // تخزين رد الذكاء الاصطناعي في قاعدة البيانات
            $ai = Message::create([
                'text' => $aiResponse,
                'group_id' => $group->id,
                'isCoach' => 1,
                'isSeen' => 0
            ]);

            return response()->json([
                'user' => $user,
                'ai'    => $ai
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء المعالجة',
                'error'   => $e->getMessage()
            ], 500);
        }
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
