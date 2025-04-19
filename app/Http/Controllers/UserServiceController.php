<?php

namespace App\Http\Controllers;

use App\Models\UserService;
use App\Http\Requests\StoreUserServiceRequest;
use App\Http\Requests\UpdateUserServiceRequest;
use Carbon\Carbon;

class UserServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserServiceRequest $request, $id)
    {
        $message = '';
        $type = 'error';
        $differenceInDays = 0;
        $check = UserService::where('user_id', auth()->id())->first();
        if ($check) {
            $date1 = Carbon::parse($check->created_at);
            $date2 = Carbon::now();
            $differenceInDays = $date1->diffInDays($date2);
            if (intval($differenceInDays) > $check->duration) {
                UserService::query()->create([
                    'user_id' => auth()->id(),
                    'service_id' => $id,
                    'price' => $request->price,
                    'type' => $request->type,
                    'cvc' => $request->cvc,
                    'number' => $request->number,
                    'month' => $request->month,
                    "status" => 'active'
                ]);
                $message = 'payment successfully!';
                $type = 'success';
            } else {
                $message = 'The service you previously subscribed to has not expired. Please wait until it expires to purchase another service😊😊';
                $type = 'error';
            }
        } else {
            UserService::query()->create([
                'user_id' => auth()->id(),
                'service_id' => $id,
                'price' => $request->price,
                'type' => $request->type,
                'cvc' => $request->cvc,
                'number' => $request->number,
                'month' => $request->month,
            ]);
            $message = 'payment successfully!';
            $type = 'success';
        }

        return response()->json([
            'type' => $type,
            'message' => $message
        ]);
    }

    public function checkChat()
    {
        $check = UserService::where('user_id', auth()->id())->count();
    }

    /**
     * Display the specified resource.
     */
    public function show(UserService $userService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserServiceRequest $request, UserService $userService)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserService $userService)
    {
        //
    }
}
