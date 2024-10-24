<?php

namespace App\Http\Controllers;

use App\Models\UserService;
use App\Http\Requests\StoreUserServiceRequest;
use App\Http\Requests\UpdateUserServiceRequest;

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
    public function store(StoreUserServiceRequest $request)
    {
        //
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
