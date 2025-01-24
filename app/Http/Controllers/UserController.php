<?php

namespace App\Http\Controllers;

use App\Models\Target;
use App\Models\Update;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
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
        return response()->json($user->load(['media']));
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
            'illness' => $request->illness,
            'gender' => $request->gender,
            'age' => $request->age,
            'lat' => $request->lat,
            'lon' => $request->lon
        ]);
        return response()->json('succ');
    }

    public function editProfile(Request $request)
    {
        User::where('id', auth()->id())->update([
            'name' => $request->name,
            'width' => $request->width,
            'height' => $request->height,
            'address' => $request->address,
            'illness' => $request->illness,
            'gender' => $request->gender,
            'age' => $request->age,
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

    public function getLastTimeUpdateDatabase()
    {
        $update = Update::get();
        $user = User::first();
        $time = count($update) ? $update->last()->updated_at : $user->updated_at;
        return response()->json([
            'lastTime' => $time
        ]);
    }
    public function profile()
    {
        $x = [];
        $y = [];
        $xx = [];
        $yy = [];
        $arrFood = [];
        $BMI = '';
        $arr = [];
        $profile = User::where('id', auth()->id())->with(['goalPlan' => function ($q) {
            $q->where('active', 1);
        }, 'goalPlan.goals', 'date'])->first();
        $today = Carbon::today();
        //get exercise 
        $CountGetdate = Target::where('user_id', auth()->id())->whereHas('goalPlan.plan', function ($q) {
            $q->where('type', '!=', 'food')->where('type', '!=', 'sleep')->where('type', '!=', 'water');
        })->where('active', 1)->get();
        if (count($CountGetdate)) {
            $sumCalories = 0;
            foreach ($CountGetdate as $data) {
                $sumCalories += $data->calories;
            }
            $totalRate = ($sumCalories / $profile->goalPlan[0]->goals->calories_max) * 100;
            $profile->totalRate = intval($totalRate);
            $caloriesForDay = Target::selectRaw('DATE(created_at) as x, SUM(calories) as y')
                ->whereHas('goalPlan.plan', function ($q) {
                    $q->where('type', '!=', 'food')->where('type', '!=', 'sleep')->where('type', '!=', 'water');
                })
                ->where('user_id', auth()->id())
                ->groupBy('x')
                ->get();
            foreach ($caloriesForDay as $data) {
                array_push($x, $data->x);
                array_push($y, $data->y);
            }
            foreach ($caloriesForDay as $data) {
                array_push($arr, ['x' => $data->x, 'y' => intval($data->y)]);
            }
            $profile->caloriesForDay = $arr;
            $profile->x = $x;
            $profile->y = $y;
            //get meal
            $FoodForDay = Target::selectRaw('DATE(created_at) as x, SUM(calories) as y')
                ->whereHas('goalPlan.plan', function ($q) {
                    $q->where('type', 'food');
                })
                ->where('user_id', auth()->id())
                ->groupBy('x')
                ->get();

            foreach ($FoodForDay as $data) {
                array_push($xx, $data->x);
                array_push($yy, $data->y);
                array_push($arrFood, ['x' => $data->x, 'y' => intval($data->y)]);
            }
            foreach ($caloriesForDay as $data) {
                array_push($arr, ['x' => $data->x, 'y' => intval($data->y)]);
            }
            $profile->FoodForDay = $arrFood;
            $profile->xx = $xx;
            $profile->yy = $yy;
            $profile->goal = $profile->goalPlan[0]->goals;
            //get water
            $WaterForDay = Target::where('user_id', auth()->id())->whereHas('goalPlan.plan', function ($q) {
                $q->where('type', 'water');
            })->whereDate('updated_at', $today)->where('active', 1)->first();
            $profile->waterForDay = $WaterForDay ? $WaterForDay->water : 0;
            //get sleep
            $SleepForDay = Target::where('user_id', auth()->id())->whereHas('goalPlan.plan', function ($q) {
                $q->where('type', 'sleep');
            })->whereDate('updated_at', $today)->where('active', 1)->first();
            $profile->sleepForDay = $SleepForDay ? $SleepForDay->sleep : 0;
            $num = $profile->width /  pow($profile->height / 100, 2);
            if ($num < 18.5) {
                $BMI = 'نقص الوزن';
            } else if ($num > 18.5 && $num < 24.5) {
                $BMI = 'وزن صحي';
            } else if ($num > 25 && $num < 29.5) {
                $BMI = 'زيادة وزن';
            } else {
                $BMI = 'سمنة';
            }
            $profile->BMI = $BMI;
        }

        return response()->json($profile);
    }

    public function deleteAccount()
    {
        User::where('id', auth()->id())->delete();
        return response()->json('user been deleted successfully');
    }
}
