<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IsHoliday
{
    public static function IsHoliday(): mixed
    {
        $date = Auth::user()->date;
        $today = Carbon::today();
        $holiday = 0;
        foreach ($date as $d) {
            if ($d->date === $today->format('Y-m-d') && $d->is_holiday) {
                $holiday = 1;
            }
        }

        return $holiday;
    }
}
