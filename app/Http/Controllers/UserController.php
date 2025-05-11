<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Goal;
use App\Models\Group;
use App\Models\Target;
use App\Models\Update;
use App\Models\User;
use App\Services\GetDate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if (Auth::user()->specialization) {
            $index = User::where('is_request', 2)->where('specialization', Auth::user()->specialization)->get();
        } else {
            $index = User::where('is_request', 2)->get();
        }
        return response()->json(['data' => $index]);
    }
    public function activeCoach($id)
    {
        $user = User::find($id);
        $user->assignRole('coach');
        $user->update([
            'is_request' => 0
        ]);
        return response()->json(['data' => 'successfully!']);
    }

    public function notActiveCoachAndAdmin($id)
    {
        $user = User::find($id);
        $user->update([
            'is_request' => 0
        ]);
        return response()->json(['data' => 'successfully!']);
    }

    public function activeAdmin($id)
    {
        $user = User::find($id);
        $user->assignRole('admin');
        $user->update([
            'is_request' => 0
        ]);
        return response()->json(['data' => 'successfully!']);
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
        $roles = $profile->getRoleNames();
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

        $arrWater = [];
        $arrSleep = [];
        $arrFood = [];
        $BMI = '';
        $arr = [];
        $profile = User::where('id', auth()->id())->with(['date'])->first();
        $goals = DB::table('targets')
            ->join('goal_plans', 'targets.goal_plan_id', '=', 'goal_plans.id')
            ->join('goals', 'goal_plans.goal_id', '=', 'goals.id')
            ->where('targets.user_id', auth()->id())
            ->select('goal_plans.goal_id', 'goals.title_ar', 'goals.title', 'targets.active', DB::raw('count(*) as total'))
            ->groupBy('goals.title_ar', 'targets.active', 'goals.title', 'goal_plans.goal_id')
            ->get();
        $mygoal = DB::table('targets')
            ->join('goal_plans', 'targets.goal_plan_id', '=', 'goal_plans.id')
            ->join('goals', 'goal_plans.goal_id', '=', 'goals.id')
            ->where('targets.user_id', auth()->id())
            ->where('active', 1)
            ->select('goals.calories_max', 'goals.calories_min', 'goal_plans.goal_id', 'goals.title_ar', 'goals.title', 'targets.active', DB::raw('count(*) as total'))
            ->groupBy('goals.calories_max', 'goals.calories_min', 'goals.title_ar', 'targets.active', 'goals.title', 'goal_plans.goal_id')
            ->get();
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
                array_push($arr, ['x' => $data->x, 'y' => intval($data->y)]);
            }
            $profile->caloriesForDay = $arr;
            //get meal
            $FoodForDay = Target::selectRaw('DATE(created_at) as x, SUM(calories) as y')
                ->whereHas('goalPlan.plan', function ($q) {
                    $q->where('type', 'food');
                })
                ->where('user_id', auth()->id())
                ->groupBy('x')
                ->get();

            foreach ($FoodForDay as $data) {
                array_push($arrFood, ['x' => $data->x, 'y' => intval($data->y)]);
            }
            foreach ($caloriesForDay as $data) {
                array_push($arr, ['x' => $data->x, 'y' => intval($data->y)]);
            }
            $profile->FoodForDay = $arrFood;
            $profile->goals = $goals;
            $profile->mygoal = $mygoal;
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
    public function getStatus()
    {
        $day = -1;
        $week = -1;
        $check = Target::where('user_id', auth()->id())->where('active', 1)->count();
        if ($check) {
            $dayd = GetDate::GetDate(2);
            $day = $dayd['day'];
            $week = $dayd['week'];
        }

        return response()->json(['day' => $day, 'week' => $week]);
    }

    public function progressGoal($id, $index)
    {
        $weekIndex = $index; // 0 للأسبوع الحالي، 1 للأسبوع السابق، 2 للأسبوع اللي قبله...

        $startweek = $weekIndex * 7;
        $endweek = $startweek + 6;
        $today = Carbon::today();

        //progress total 
        $userId = auth()->id();

        $exerciseCalories = DB::table('targets')
            ->where('user_id', $userId)
            ->join('goal_plans', 'targets.goal_plan_id', '=', 'goal_plans.id')
            ->join('plans', 'goal_plans.plan_id', '=', 'plans.id')
            ->where('goal_plans.goal_id', $id)
            ->where('plans.type', '!=', 'food')
            ->sum('targets.calories');

        $mealCalories = DB::table('targets')
            ->where('user_id', $userId)
            ->join('goal_plans', 'targets.goal_plan_id', '=', 'goal_plans.id')
            ->join('plans', 'goal_plans.plan_id', '=', 'plans.id')
            ->where('goal_plans.goal_id', $id)
            ->where('plans.type', '!=', 'food')
            ->sum('targets.calories');

        // صافي السعرات المحروقة = تمارين - وجبات
        $netCaloriesBurned = $exerciseCalories - $mealCalories;

        // بيانات الهدف
        $goal = DB::table('goals')->where('id', $id)->first();

        // النسبة المئوية
        $percentage = 0;
        $percentage = ($netCaloriesBurned / $goal->calories_max) * 100;

        // حساب الوزن المتوقع بعد الهدف (بفرض 7700 سعرة = 1 كجم)
        $weightLost = $netCaloriesBurned / 7700;
        $finalWeight = Auth::user()->width - $weightLost;
        //dorw

        //food
        $calories_food =   Target::selectRaw('DATE(created_at) as date, SUM(calories) as total_calories_food')
            ->whereHas('goalPlan.plan', function ($q) {
                $q->where('type', 'food');
            })
            ->whereHas('goalPlan', function ($q) use ($id) {
                $q->where('goal_id', $id);
            })
            ->where('check', '!=', 0)
            ->where('user_id', auth()->id())
            ->where('created_at', '>=', $today->copy()->subDays(6))
            ->groupBy('date')
            ->get();

        $dates_calories_food = collect(range($startweek, $endweek))->mapWithKeys(function ($daysAgo) {
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
        $datesCaloriesFood = $dates_calories_food->sortBy('x')->values();


        //get calories tatal
        $calories =   Target::selectRaw('DATE(created_at) as date, SUM(calories) as total_calories')
            ->whereHas('goalPlan.plan', function ($q) {
                $q->where('type', '!=', 'food');
            })
            ->whereHas('goalPlan', function ($q) use ($id) {
                $q->where('goal_id', $id);
            })
            ->where('check', '!=', 0)
            ->where('user_id', auth()->id())
            ->where('created_at', '>=', $today->copy()->subDays(6))
            ->groupBy('date')
            ->get();

        $dates_calories = collect(range($startweek, $endweek))->mapWithKeys(function ($daysAgo) {
            $date_c = Carbon::today()->subDays($daysAgo)->toDateString();
            return [$date_c => ['date' => $date_c, 'total_calories' => 0]];
        });
        $dates_calories = $dates_calories->map(function ($item) use ($calories) {
            $match = $calories->firstWhere('date', $item['date']);
            return [
                'x' => $item['date'],
                'y' => $match ? $match->total_calories : 0
            ];
        });
        $totalcaloriesE = $dates_calories->sortBy('x')->values();


        //get sleep tatal
        $sleeps = DB::table('targets')
            ->selectRaw('DATE(targets.created_at) as date, SUM(sleep) as total_sleep')
            ->where('targets.created_at', '>=', $today->copy()->subDays(6))
            ->join('goal_plans', 'targets.goal_plan_id', '=', 'goal_plans.id')
            ->groupBy('date')
            ->where('goal_plans.goal_id', $id)
            ->where('user_id', auth()->id())
            ->orderBy('date', 'ASC')
            ->get();

        $dates_sleep = collect(range($startweek, $endweek))->mapWithKeys(function ($daysAgo) {
            $date_ss = Carbon::today()->subDays($daysAgo)->toDateString();
            return [$date_ss => ['date' => $date_ss, 'total_sleep' => 0]];
        });
        $dates_sleep = $dates_sleep->map(function ($item) use ($sleeps) {
            $match = $sleeps->firstWhere('date', $item['date']);
            return [
                'x' => $item['date'],
                'y' => $match ? $match->total_sleep : 0
            ];
        });
        $totalSleep = $dates_sleep->sortBy('x')->values();
        $goal = Goal::find($id);
        //get water tatal
        $waters = DB::table('targets')
            ->selectRaw('DATE(targets.created_at) as date, SUM(water) as total_water')
            ->join('goal_plans', 'targets.goal_plan_id', '=', 'goal_plans.id')
            ->groupBy('date')
            ->where('goal_plans.goal_id', $id)
            ->where('targets.created_at', '>=', $today->copy()->subDays(6))
            ->where('user_id', auth()->id())
            ->orderBy('date', 'ASC')
            ->get();

        $dates_water = collect(range($startweek, $endweek))->mapWithKeys(function ($daysAgo) {
            $date_ww = Carbon::today()->subDays($daysAgo)->toDateString();
            return [$date_ww => ['date' => $date_ww, 'total_water' => 0]];
        });
        $dates_water = $dates_water->map(function ($item) use ($waters) {
            $match = $waters->firstWhere('date', $item['date']);
            return [
                'x' => $item['date'],
                'y' => $match ? $match->total_water : 0
            ];
        });

        $totalWater = $dates_water->sortBy('x')->values();

        return response()->json([
            "datesCaloriesFood" => $datesCaloriesFood,
            "datesCaloriesE" => $totalcaloriesE,
            "totalSleep" => $totalSleep,
            "totalWater" => $totalWater,
            'exercise_calories' => $exerciseCalories,
            'meal_calories' => $mealCalories,
            'net_burned' => $netCaloriesBurned,
            'goal' => $goal,
            'percentage' => round($percentage, 2),
            'start_weight' =>  Auth::user()->width,
            'final_weight' => round($finalWeight, 2),
        ]);
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
            'specialization' => $request->specialization,
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
            'specialization' => $request->specialization,
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
        // chat start
        //get count chat session coach
        $countChatCoachSession = Chat::where("type", '!=', 'bot')->count();
        //get count chat session coach for coach
        $countChatCoachSessionForCoach = Chat::where("type", '!=', 'bot')->whereHas('user', function ($q) {
            $q->where('users.id', auth()->id());
        })->count();
        //get request
        $countRequestGoal = User::where('is_request', 1)->count();
        //get request coach
        $countRequestCoach = User::where('is_request', 2)->count();
        //chat end 
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
        //get totle for not super
        $drinkUserWaterForNotCoach =  Target::selectRaw('SUM(water) as y')
            ->whereHas('goalPlan.plan', function ($q) {
                $q->where('type', 'water');
            })
            ->whereHas('goalPlan.goals', function ($q) {
                $q->where('goals.id', Auth::user()->specialization);
            })
            ->where('check', '!=', 0)
            ->whereDate('updated_at', $today)
            ->first();
        $drinkUserWaterTotalForNotCoach = intval(($drinkUserWaterForNotCoach->y / $countUserSginUp));
        //get user sleep rate
        // for not coach
        $drinkUserSleepForNotCoach =  Target::selectRaw('SUM(sleep) as y')
            ->whereHas('goalPlan.plan', function ($q) {
                $q->where('type', 'sleep');
            })
            ->whereHas('goalPlan.goals', function ($q) {
                $q->where('goals.id', Auth::user()->specialization);
            })
            ->where('check', '!=', 0)
            ->whereDate('updated_at', $today)
            ->first();
        $drinkUserSleepTotalForNotCoach = intval(($drinkUserSleepForNotCoach->y / $countUserSginUp));
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
        //for coach
        $totalCalroiesForNotCoach =  Target::selectRaw('SUM(calories) as y')
            ->whereHas('goalPlan.plan', function ($q) {
                $q->where('type', '!=', 'food');
            })
            ->whereHas('goalPlan.goals', function ($q) {
                $q->where('goals.id', Auth::user()->specialization);
            })
            ->where('check', '!=', 0)
            ->whereDate('updated_at', $today)
            ->first();
        $TotalCaloriesRateForNotCoach = intval(($totalCalroiesForNotCoach->y / $countUserSginUp));


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
            $date_ww = Carbon::today()->subDays($daysAgo)->toDateString();
            return [$date_ww => ['date' => $date_ww, 'total_water' => 0]];
        });
        $dates_water = $dates_water->map(function ($item) use ($waters) {
            $match = $waters->firstWhere('date', $item['date']);
            return [
                'x' => $item['date'],
                'y' => $match ? $match->total_water : 0
            ];
        });

        $totalWater = $dates_water->sortBy('x')->values();
        //get water tatal for not coach
        $watersForNotCoach = DB::table('targets')
            ->selectRaw('DATE(targets.created_at) as date, SUM(water) as total_water')
            ->join('goal_plans', 'goal_plans.id', '=', 'targets.goal_plan_id')
            ->where('goal_plans.goal_id', Auth::user()->specialization)
            ->where('targets.created_at', '>=', $today->copy()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $dates_water_for_not_coach = collect(range(0, 6))->mapWithKeys(function ($daysAgo) {
            $date_w = Carbon::today()->subDays($daysAgo)->toDateString();
            return [$date_w => ['date' => $date_w, 'total_water' => 0]];
        });
        $dates_water_for_not_coach = $dates_water_for_not_coach->map(function ($item) use ($watersForNotCoach) {
            $match = $watersForNotCoach->firstWhere('date', $item['date']);
            return [
                'x' => $item['date'],
                'y' => $match ? $match->total_water : 0
            ];
        });

        $totalWaterForNotCoach = $dates_water_for_not_coach->sortBy('x')->values();
        //get sleep tatal
        $sleeps = DB::table('targets')
            ->selectRaw('DATE(created_at) as date, SUM(sleep) as total_sleep')
            ->where('created_at', '>=', $today->copy()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $dates_sleep = collect(range(0, 6))->mapWithKeys(function ($daysAgo) {
            $date_ss = Carbon::today()->subDays($daysAgo)->toDateString();
            return [$date_ss => ['date' => $date_ss, 'total_sleep' => 0]];
        });
        $dates_sleep = $dates_sleep->map(function ($item) use ($sleeps) {
            $match = $sleeps->firstWhere('date', $item['date']);
            return [
                'x' => $item['date'],
                'y' => $match ? $match->total_sleep : 0
            ];
        });

        $totalSleep = $dates_sleep->sortBy('x')->values();
        //get sleep tatal for coach
        $sleepsForCoach = DB::table('targets')
            ->selectRaw('DATE(targets.created_at) as date, SUM(sleep) as total_sleep')
            ->join('goal_plans', 'goal_plans.id', '=', 'targets.goal_plan_id')
            ->where('goal_plans.goal_id', Auth::user()->specialization)
            ->where('targets.created_at', '>=', $today->copy()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $dates_sleep_for_not_coach = collect(range(0, 6))->mapWithKeys(function ($daysAgo) {
            $date_s = Carbon::today()->subDays($daysAgo)->toDateString();
            return [$date_s => ['date' => $date_s, 'total_sleep' => 0]];
        });
        $dates_sleep_for_not_coach = $dates_sleep_for_not_coach->map(function ($item) use ($sleepsForCoach) {
            $match = $sleepsForCoach->firstWhere('date', $item['date']);
            return [
                'x' => $item['date'],
                'y' => $match ? $match->total_sleep : 0
            ];
        });

        $totalSleepForNotCoach = $dates_sleep_for_not_coach->sortBy('x')->values();

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
            $date_c = Carbon::today()->subDays($daysAgo)->toDateString();
            return [$date_c => ['date' => $date_c, 'total_calories' => 0]];
        });
        $dates_calories = $dates_calories->map(function ($item) use ($calories) {
            $match = $calories->firstWhere('date', $item['date']);
            return [
                'x' => $item['date'],
                'y' => $match ? $match->total_calories : 0
            ];
        });

        $totalCalories = $dates_calories->sortBy('x')->values();
        //get calories tatal for not coach
        $caloriesForNotCoach =   Target::selectRaw('DATE(created_at) as date, SUM(calories) as total_calories')
            ->whereHas('goalPlan.plan', function ($q) {
                $q->where('type', '!=', 'food');
            })
            ->whereHas('goalPlan', function ($q) {
                $q->where('goal_id', Auth::user()->specialization);
            })
            ->where('check', '!=', 0)
            ->where('created_at', '>=', $today->copy()->subDays(6))
            ->groupBy('date')
            ->get();

        $dates_calories_for_not_coach = collect(range(0, 6))->mapWithKeys(function ($daysAgo) {
            $date_for_coach = Carbon::today()->subDays($daysAgo)->toDateString();
            return [$date_for_coach => ['date' => $date_for_coach, 'total_calories' => 0]];
        });
        $dates_calories_for_not_coach = $dates_calories_for_not_coach->map(function ($item) use ($caloriesForNotCoach) {
            $match = $caloriesForNotCoach->firstWhere('date', $item['date']);
            return [
                'x' => $item['date'],
                'y' => $match ? $match->total_calories : 0
            ];
        });

        $totalCaloriesForNotCoach = $dates_calories->sortBy('x')->values();

        //get progress bot 

        //count chat with bot today

        $countChatWithBotToday = Chat::where("type", 'bot')->whereDate('updated_at', $today)->count();
        $countChatWithBotTodayRate = intval(($countChatWithBotToday / $countChatSession) * 100);
        //count coach  today
        $countChatWithCaochToday = Chat::where("type", '!=', 'bot')->whereDate('updated_at', $today)->count();
        $countChatWithCaochTodayRate = intval(($countChatWithCaochToday / $countChatSession) * 100);
        //count my chat today
        $countChatWithCaochTodayForNotCoach = Chat::where("type", '!=', 'bot')->whereHas('user', function ($q) {
            $q->where('users.id', auth()->id());
        })->whereDate('updated_at', $today)->count();
        $countChatWithCaochTodayRateForNotCoach = intval(($countChatWithCaochTodayForNotCoach / $countChatSession) * 100);

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

        //get total calories for not coach
        $calories_food_for_not_coach =   Target::selectRaw('DATE(created_at) as date, SUM(calories) as total_calories_food')
            ->whereHas('goalPlan.plan', function ($q) {
                $q->where('type', 'food');
            })
            ->whereHas('goalPlan', function ($q) {
                $q->where('goal_id', Auth::user()->specialization);
            })
            ->where('check', '!=', 0)
            ->where('created_at', '>=', $today->copy()->subDays(6))
            ->groupBy('date')
            ->get();

        $dates_calories_food_for_not_coach = collect(range(0, 6))->mapWithKeys(function ($daysAgo) {
            $date_xx = Carbon::today()->subDays($daysAgo)->toDateString();
            return [$date_xx => ['date' => $date_xx, 'total_calories_food' => 0]];
        });
        $dates_calories_food_for_not_coach = $dates_calories_food_for_not_coach->map(function ($item) use ($calories_food_for_not_coach) {
            $match = $calories_food_for_not_coach->firstWhere('date', $item['date']);
            return [
                'x' => $item['date'],
                'y' => $match ? $match->total_calories_food : 0
            ];
        });

        $totalCaloriesFoodForNotCoach = $dates_calories_food_for_not_coach->sortBy('x')->values();

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
            $d->countCoach = intval($countCoach);
            $d->countChatSession = $countChatSession;
            $d->countRequestCoach = $countRequestCoach;
            $d->countRequestGoal = $countRequestGoal;
            $d->countChatWithCaochTodayRateForNotCoach = $countChatWithCaochTodayRateForNotCoach;
            $d->totalWaterForNotCoach = $totalWaterForNotCoach;
            $d->countChatCoachSessionForCoach = $countChatCoachSessionForCoach;
            $d->countChatCoachSession = $countChatCoachSession;
            $d->TotalCaloriesRateForNotCoach = $TotalCaloriesRateForNotCoach;
            $d->countChatBotSession = $countChatBotSession;
            $d->drinkUserWaterTotal = $drinkUserWaterTotal;
            $d->totalCaloriesForNotCoach = $totalCaloriesForNotCoach;
            $d->drinkUserWaterTotalForNotCoach = $drinkUserWaterTotalForNotCoach;
            $d->drinkUserSleepTotal = $drinkUserSleepTotal;
            $d->drinkUserSleepTotalForNotCoach = $drinkUserSleepTotalForNotCoach;
            $d->countUserExercice = $countUserExercice;
            $d->TotalCaloriesRate = $TotalCaloriesRate;
            $d->getLastUserLogin = $getLastUserLogin;
            $d->totalSleepForNotCoach = $totalSleepForNotCoach;
            $d->totalWater = $totalWater;
            $d->totalSleep = $totalSleep;
            $d->countChatWithBotTodayRate = $countChatWithBotTodayRate;
            $d->totalCalories = $totalCalories;
            $d->countChatWithBotToday = $countChatWithBotToday;
            $d->countChatWithCaochTodayRate = $countChatWithCaochTodayRate;
            $d->totalCaloriesFoodForNotCoach = $totalCaloriesFoodForNotCoach;
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
