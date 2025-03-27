<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Group;
use App\Models\Target;
use App\Models\Update;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index($id = null)
    {
        $users = User::get();
        if ($id) {
            $users = User::where('id', $id)->get();
        }

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
        $user = User::where('id', auth()->id())->first();
        $user->update([
            'width' => $request->width,
            'height' => $request->height,
            'address' => $request->address,
            'illness' => $request->illness,
            'gender' => $request->gender,
            'age' => $request->age,
            'lat' => $request->lat,
            'lon' => $request->lon
        ]);
        return response()->json(['data' => $user, 'message' => 'succ']);
    }

    public function editProfile(Request $request)
    {
        $user = User::where('id', auth()->id())->first();
        $user->update([
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
        return response()->json(['data' => $user, 'message' => 'edit profile successfully!', 'type' => 'success']);
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
    public function getRequestAdmin()
    {
        $index = User::where('is_request', 1)->get();
        return response()->json(['data' => $index]);
    }
    public function getRequestCoach()
    {
        $index = User::where('is_request', 2)->get();
        return response()->json(['data' => $index]);
    }
    public function showUser($id)
    {

        $x = [];
        $y = [];
        $xx = [];
        $yy = [];
        $arrFood = [];
        $BMI = '';
        $arr = [];
        $profile = User::where('id', $id)->with(['goalPlan' => function ($q) {
            $q->where('active', 1);
        }, 'goalPlan.goals', 'date'])->first();
        $today = Carbon::today();
        //get exercise 
        $CountGetdate = Target::where('user_id', $id)->whereHas('goalPlan.plan', function ($q) {
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
                ->where('user_id', $id)
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
            $WaterForDay = Target::where('user_id', $id)->whereHas('goalPlan.plan', function ($q) {
                $q->where('type', 'water');
            })->whereDate('updated_at', $today)->where('active', 1)->first();
            $profile->waterForDay = $WaterForDay ? $WaterForDay->water : 0;
            //get sleep
            $SleepForDay = Target::where('user_id', $id)->whereHas('goalPlan.plan', function ($q) {
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

        return response()->json(['data' => $profile]);
    }
    public function profile()
    {
        $x = [];
        $y = [];
        $xx = [];
        $yy = [];
        $arrWater = [];
        $arrSleep = [];
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
            //get water every day
            $WaterForEveryDay = Target::selectRaw('DATE(created_at) as x, SUM(water) as y')
                ->whereHas('goalPlan.plan', function ($q) {
                    $q->where('type', 'water');
                })
                ->where('user_id', auth()->id())
                ->groupBy('x')
                ->get();

            foreach ($WaterForEveryDay as $data) {
                array_push($arrWater, ['x' => $data->x, 'y' => intval($data->y)]);
            }
            //get sleep
            $SleepForDay = Target::where('user_id', auth()->id())->whereHas('goalPlan.plan', function ($q) {
                $q->where('type', 'sleep');
            })->whereDate('updated_at', $today)->where('active', 1)->first();
            // get sleep every day
            $SleepForEveryDay = Target::selectRaw('DATE(created_at) as x, SUM(sleep) as y')
                ->whereHas('goalPlan.plan', function ($q) {
                    $q->where('type', 'sleep');
                })
                ->where('user_id', auth()->id())
                ->groupBy('x')
                ->get();

            foreach ($SleepForEveryDay as $data) {
                array_push($arrSleep, ['x' => $data->x, 'y' => intval($data->y)]);
            }
            $profile->WaterForEveryDay = $arrWater;
            $profile->SleepForEveryDay = $arrSleep;
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

    public function send_request_admin(Request $request)
    {
        $user = User::where('id', auth()->id())->first();
        $user->update([
            'is_request' => 1,
            'description' => $request->description,
            'why_admin' => $request->why_admin,
        ]);
        if ($request->media) {
            $user->addMediaFromRequest('media')->toMediaCollection('users');
        }
        if ($request->media_file) {
            $user->addMediaFromRequest('media_file')->toMediaCollection('users');
        }
        return response()->json(['message' => 'send request successfully!']);
    }

    public function send_request_coach(Request $request)
    {
        $user = User::where('id', auth()->id())->first();
        $user->update([
            'is_request' => 2,
            'description' => $request->description,
            'communication' => $request->communication,
            'analysis' => $request->analysis,
            'education' => $request->education,
            'development' => $request->development,
        ]);
        if ($request->media) {
            $user->addMediaFromRequest('media')->toMediaCollection('users');
        }
        if ($request->media_file) {
            $user->addMediaFromRequest('media_file')->toMediaCollection('users');
        }
        return response()->json(['message' => 'send request successfully!']);
    }

    public function progressAdmin()
    {
        $today = Carbon::today();

        $data[] = (object) ["countUserSginUp" => null];
        //get number user register
        $countUserSginUp = User::count();
        //get number coach
        $countCoach = User::role('coach')->count();
        //get count chat session
        $countChatSession = Chat::count();
        //get count chat session coach
        $countChatCoachSession = Chat::where("type", '!=', 'bot')->count();
        //get count chat session bot
        $countChatBotSession = Chat::where("type", 'bot')->count();
        //get drink user water rate
        $drinkUserWater =  Target::selectRaw('SUM(water) as y')
            ->whereHas('goalPlan.plan', function ($q) {
                $q->where('type', 'water');
            })
            ->where('check', '!=', 0)
            ->whereDate('updated_at', $today)
            ->first();
        $drinkUserWaterTotal = intval(($drinkUserWater->y / $countUserSginUp));
        //get user sleep rate
        $drinkUserSleep =  Target::selectRaw('SUM(sleep) as y')
            ->whereHas('goalPlan.plan', function ($q) {
                $q->where('type', 'sleep');
            })
            ->where('check', '!=', 0)
            ->whereDate('updated_at', $today)
            ->first();
        $drinkUserSleepTotal = intval(($drinkUserSleep->y / $countUserSginUp));
        //count exercice work user
        $countUserExercice =  Target::whereHas('goalPlan.plan', function ($q) {
            $q->where('type', '!=', 'sleep')->where('type', '!=', 'water')->where('type', '!=', 'food');
        })
            ->where('check', '!=', 0)
            ->whereDate('updated_at', $today)
            ->count();
        //get total calories 
        $totalCalroies =  Target::selectRaw('SUM(calories) as y')
            ->whereHas('goalPlan.plan', function ($q) {
                $q->where('type', '!=', 'food');
            })
            ->where('check', '!=', 0)
            ->whereDate('updated_at', $today)
            ->first();
        $TotalCaloriesRate = intval(($totalCalroies->y / $countUserSginUp));


        //drow
        //last user login 7 days
        $logins = DB::table('users')
            ->selectRaw('DATE(last_login_at) as date, COUNT(*) as count')
            ->where('last_login_at', '>=', $today->copy()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // تجهيز تواريخ 7 أيام الماضية كبداية
        $dates_users = collect(range(0, 6))->mapWithKeys(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo)->toDateString();
            return [$date => ['date' => $date, 'count' => 0]];
        });

        // دمج النتائج مع الـ default values
        $dates_users = $dates_users->map(function ($item) use ($logins) {
            $match = $logins->firstWhere('date', $item['date']);
            return [
                'x' => $item['date'],
                'y' => $match ? $match->count : 0
            ];
        });
        $getLastUserLogin = $dates_users->sortBy('x')->values();
        //get water tatal
        $waters = DB::table('targets')
            ->selectRaw('DATE(created_at) as date, SUM(water) as total_water')
            ->where('created_at', '>=', $today->copy()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $dates_water = collect(range(0, 6))->mapWithKeys(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo)->toDateString();
            return [$date => ['date' => $date, 'total_water' => 0]];
        });
        $dates_water = $dates_water->map(function ($item) use ($waters) {
            $match = $waters->firstWhere('date', $item['date']);
            return [
                'x' => $item['date'],
                'y' => $match ? $match->total_water : 0
            ];
        });

        $totalWater = $dates_water->sortBy('x')->values();

        //get sleep tatal
        $sleeps = DB::table('targets')
            ->selectRaw('DATE(created_at) as date, SUM(sleep) as total_sleep')
            ->where('created_at', '>=', $today->copy()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $dates_sleep = collect(range(0, 6))->mapWithKeys(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo)->toDateString();
            return [$date => ['date' => $date, 'total_sleep' => 0]];
        });
        $dates_sleep = $dates_sleep->map(function ($item) use ($sleeps) {
            $match = $sleeps->firstWhere('date', $item['date']);
            return [
                'x' => $item['date'],
                'y' => $match ? $match->total_sleep : 0
            ];
        });

        $totalSleep = $dates_sleep->sortBy('x')->values();

        //get calories tatal
        $calories =   Target::selectRaw('DATE(created_at) as date, SUM(calories) as total_calories')
            ->whereHas('goalPlan.plan', function ($q) {
                $q->where('type', '!=', 'food');
            })
            ->where('check', '!=', 0)
            ->where('created_at', '>=', $today->copy()->subDays(6))
            ->groupBy('date')
            ->get();

        $dates_calories = collect(range(0, 6))->mapWithKeys(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo)->toDateString();
            return [$date => ['date' => $date, 'total_calories' => 0]];
        });
        $dates_calories = $dates_calories->map(function ($item) use ($calories) {
            $match = $calories->firstWhere('date', $item['date']);
            return [
                'x' => $item['date'],
                'y' => $match ? $match->total_calories : 0
            ];
        });

        $totalCalories = $dates_calories->sortBy('x')->values();

        //get progress bot 

        //count chat with bot today

        $countChatWithBotToday = Chat::where("type", 'bot')->whereDate('updated_at', $today)->count();
        //count coach with bot today
        $countChatWithCaochToday = Chat::where("type", '!=', 'bot')->whereDate('updated_at', $today)->count();

        // get progress food

        //get total calories
        $calories_food =   Target::selectRaw('DATE(created_at) as date, SUM(calories) as total_calories_food')
            ->whereHas('goalPlan.plan', function ($q) {
                $q->where('type', 'food');
            })
            ->where('check', '!=', 0)
            ->where('created_at', '>=', $today->copy()->subDays(6))
            ->groupBy('date')
            ->get();

        $dates_calories_food = collect(range(0, 6))->mapWithKeys(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo)->toDateString();
            return [$date => ['date' => $date, 'total_calories_food' => 0]];
        });
        $dates_calories_food = $dates_calories_food->map(function ($item) use ($calories_food) {
            $match = $calories_food->firstWhere('date', $item['date']);
            return [
                'x' => $item['date'],
                'y' => $match ? $match->total_calories_food : 0
            ];
        });

        $totalCaloriesFood = $dates_calories_food->sortBy('x')->values();

        //progress payment and service

        //total payment
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // اجمالي الإيرادات هذا الشهر
        $totalRevenue = DB::table('user_services')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('price');

        // عدد المشتركين الجدد هذا الشهر
        $newSubscribers = DB::table('users')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        $totalSubscriptions = DB::table('user_services')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        // عدد الاشتراكات اللي تم إلغاءها هذا الشهر
        $canceledSubscriptions = DB::table('user_services')
            ->where('status', 'canceled')
            ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
            ->count();
        $cancellationRate = $totalSubscriptions > 0
            ? ($canceledSubscriptions / $totalSubscriptions) * 100
            : 0;

        // top users
        $topUsers = Target::select('user_id', DB::raw('COUNT(DISTINCT DATE(created_at)) as active_days'))
            ->groupBy('user_id')
            ->orderByDesc('active_days')
            ->take(10)
            ->with('users:id,name,email')
            ->get();


        foreach ($data as $d) {
            $d->countUserSginUp = $countUserSginUp;
            $d->countCoach = $countCoach;
            $d->countChatSession = $countChatSession;
            $d->countChatCoachSession = $countChatCoachSession;
            $d->countChatBotSession = $countChatBotSession;
            $d->drinkUserWaterTotal = $drinkUserWaterTotal;
            $d->drinkUserSleepTotal = $drinkUserSleepTotal;
            $d->countUserExercice = $countUserExercice;
            $d->TotalCaloriesRate = $TotalCaloriesRate;
            $d->getLastUserLogin = $getLastUserLogin;
            $d->totalWater = $totalWater;
            $d->totalSleep = $totalSleep;
            $d->totalCalories = $totalCalories;
            $d->countChatWithBotToday = $countChatWithBotToday;
            $d->countChatWithCaochToday = $countChatWithCaochToday;
            $d->totalCaloriesFood = $totalCaloriesFood;
            $d->totalRevenue = $totalRevenue;
            $d->newSubscribers = $newSubscribers;
            $d->canceledSubscriptions = $canceledSubscriptions;
            $d->cancellationRate = $cancellationRate;
            $d->topUsers = $topUsers;
        }

        return response()->json(['data' => $data]);
    }
}
