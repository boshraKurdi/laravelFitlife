<?php

namespace App\Services;

class Distance
{
    public static function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371): mixed
    {
        // تحويل الدرجات إلى راديان
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        // حساب الفرق في خطوط الطول والعرض
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        // حساب المسافة باستخدام صيغة هافرسين
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return round($angle * $earthRadius);
    }
}
