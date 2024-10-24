<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::get();
        return response()->json($users);
    }
    public function index2()
    {
        $users = User::get();
        return response()->json($users);
    }
    public function coachs()
    {
        $coachs = User::role('coach')->with('media')->get();
        return response()->json($coachs);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json('user been deleted successfully');
    }

    public function updata(Request $request)
    {
        User::where('id', auth()->id())->update([
            'width' => $request->width,
            'height' => $request->height,
            'address' => $request->address,
            'lat' => $request->lat,
            'lon' => $request->lon
        ]);
        return response()->json('succ');
    }
    public function checkEmail($email)
    {
        $check = User::where('email', $email)->get();
        return response()->json($check);
    }
}
