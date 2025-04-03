<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GetDate
{
    public static function GetDate($du): mixed
    {
        $dates = Auth::user()->date; // قائمة التواريخ
        $today = Carbon::today(); // تاريخ اليوم



        foreach ($dates as $index => $d) {
            if ($d->date === $today->format('Y-m-d')) {
                return [
                    'day' => intval(($index % 7)) + 1,  // ترتيب اليوم داخل المصفوفة (الأول، الثاني، الثالث...)
                    'week' => intval(intval(($index / 7)) % $du) + 1,  // رقم الأسبوع داخل الشهر
                ];
            }
        }

        return ['day' => -1, 'week' => -1];
    }
}
