<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthUpdateRequest;
use App\Mail\VerifyEmailCodeMail;
use App\Models\EmailVerificationCode;
use App\Models\User;
use App\Observers\GoalPlanObserver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => app()->getLocale() == 'en' ? 'the email or password not validation' : 'الايميل او كلمة السر غير صالحين',
                'lan' => app()->getLocale()
            ], 401);
        }
        $observer = new GoalPlanObserver();
        $observer->update();

        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function loginPanel(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => app()->getLocale() == 'en' ? 'The email or password is invalid' : 'الايميل او كلمة السر غير صالحين',
                'lan' => app()->getLocale()
            ], 401);
        }

        $user = Auth::user();
        $user->role;


        if (!$user->hasRole('admin') && !$user->hasRole('coach') && !$user->hasRole('super admin')) {
            Auth::logout();

            return response()->json([
                'status' => 'error',
                'message' => app()->getLocale() == 'en' ? 'Access denied. Admins only.' : 'تم الرفض. هذا الدخول مخصص للمدراء فقط.',
                'lan' => app()->getLocale()
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'message' => 'login successfully',
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }


    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $observer = new GoalPlanObserver();
        $observer->update();

        $token = Auth::login($user);
        $code = (string) random_int(100000, 999999);
        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        // إرسال الإيميل عبر Mailtrap
        Mail::to($user->email)->send(new VerifyEmailCodeMail($user, $code));
        return response()->json([
            'status' => 'success',
            'message' => 'The account has been created. Check your email to confirm the account.',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function user()
    {
        return response()->json(Auth::user());
    }

    public function update(AuthUpdateRequest $request)
    {

        $user = User::query()->where('id', Auth::user()->id)->first();
        $user->update([
            'width' => $request->width,
            'height' => $request->height,
            'address' => $request->address,
            'gender' => $request->gender,
            'illness' => $request->illness,
            'age' => $request->age,
            'lat' => $request->lat,
            'lon' => $request->lon,
            'days' => $request->days
        ]);
        $observer = new GoalPlanObserver();
        $observer->update();
        return response()->json([
            'data' => $user,
            'message' => 'user has been updated successfully'
        ]);
    }
}
