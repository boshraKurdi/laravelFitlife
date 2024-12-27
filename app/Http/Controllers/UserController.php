<?php

namespace App\Http\Controllers;

use App\Models\Target;
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
    public function checkEmail($email)
    {
        $check = User::where('email', $email)->get();
        return response()->json($check);
    }

    public function getLastTimeUpdateDatabase()
    {
        $user = User::query()->first();
        return response()->json([
            'lastTime' => $user->updated_at
        ]);
    }
    public function profile()
    {
        $x = [0];
        $y = [0];
        $BMI = '';
        $arr = [];
        $today = Carbon::today();
        $sumCalories = 0;
        $profile = User::where('id', auth()->id())->with(['goalPlanLevel', 'goalPlanLevel.goals'])->first();
        foreach ($profile->goalPlanLevel as $data) {
            $sumCalories += $data->pivot->calories;
        }
        $totalRate = ($sumCalories / $profile->goalPlanLevel[0]->goals->calories_max) * 100;
        $profile->totalRate = intval($totalRate);
        $caloriesForDay = Target::selectRaw('DATE(created_at) as x, SUM(calories) as y')
            ->where('user_id', auth()->id())
            ->groupBy('x')
            ->get();
        foreach ($caloriesForDay as $data) {
            array_push($x, $data->x);
            array_push($y, $data->y);
        }
        array_push($arr, ['x' => 0, 'y' => 0]);
        foreach ($caloriesForDay as $data) {
            array_push($arr, ['x' => $data->x, 'y' => intval($data->y)]);
        }
        $profile->caloriesForDay = $arr;
        $profile->x = $x;
        $profile->y = $y;
        $profile->goal = $profile->goalPlanLevel[0]->goals;
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

        return response()->json($profile);
    }
}
