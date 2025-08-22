<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmailCodeMail;
use App\Models\EmailVerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailVerificationController extends Controller
{
    // إرسال الرمز (أثناء/بعد التسجيل أو من زر "إعادة إرسال")
    public function send(Request $request)
    {
        $user = $request->user(); // يتطلب auth()
        // أنشئ رمزًا من 6 أرقام
        $code = (string) random_int(100000, 999999);

        EmailVerificationCode::where('user_id', $user->id)
            ->whereNull('consumed_at')->delete(); // تنظيف القديمة

        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new VerifyEmailCodeMail($user, $code));

        return response()->json(['message' => 'تم إرسال رمز التحقق إلى بريدك.']);
    }

    // التحقق من الرمز
    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = $request->user();

        $record = EmailVerificationCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->whereNull('consumed_at')
            ->first();

        if (!$record) {
            return response()->json(['message' => 'رمز غير صحيح'], 422);
        }

        if ($record->expires_at->isPast()) {
            return response()->json(['message' => 'انتهت صلاحية الرمز'], 422);
        }

        // نجح التحقق
        $user->forceFill(['email_verified_at' => now()])->save();
        $record->update(['consumed_at' => now()]);
        // احذف أي رموز قديمة متبقية
        EmailVerificationCode::where('user_id', $user->id)
            ->whereNull('consumed_at')
            ->delete();

        return response()->json(['message' => 'تم تأكيد البريد بنجاح.']);
    }
}
