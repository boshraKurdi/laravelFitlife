<?php

namespace App\Http\Controllers;

use App\Models\Target;
use App\Http\Requests\StoreTargetRequest;
use App\Http\Requests\StoreTargetSleepRequest;
use App\Http\Requests\StoreTargetWaterRequest;
use App\Http\Requests\UpdateTargetRequest;
use App\Models\Date;
use App\Models\GoalPlan;
use App\Models\User;
use App\Observers\GoalPlanObserver;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TargetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTargetRequest $request)
    {
        $goal_id = Target::where('user_id', auth()->id())->whereHas('goalPlan.plan', function ($q) {
            $q->where('type', 'food');
        })->WhereHas('goalPlan', function ($q) use ($request) {
            $q->where('plan_id', $request->plan_id);
        })->with('goalPLan')->first();
        $message = '';
        $data = 'error';
        $currentDate = Carbon::now();
        if ($goal_id) {
            $countActive = Target::where('user_id', auth()->id())->where('active', 1)->count();
            if ($countActive) {
                $countDay = Target::where('user_id', auth()->id())->where('goal_plan_id', $goal_id->goal_plan_id)->whereDate('created_at', $currentDate)->get();
                if (count($countDay)) {
                    Target::where('user_id', auth()->id())->where('goal_plan_id', $goal_id->goal_plan_id)->whereDate('created_at', $currentDate)->delete();
                }
                for ($i = 0; $i < count($request->check); $i++) {
                    Target::create([
                        'user_id' => auth()->id(),
                        'goal_plan_id' => $goal_id->goal_plan_id,
                        'calories' => $request->calories[$i],
                        'check' => $request->check[$i],
                        'active' => true,
                    ]);
                    $observer = new GoalPlanObserver();
                    $observer->update();
                    $data = 'success';
                    $message = app()->getLocale() == 'en'  ? 'Your progress in the plan meals has been recorded. Keep going. 😎😍' : 'لقد تم تسجيل تقدمك في خطة الوجبات. استمر. 😎😍';
                }
            } else {
                $message = app()->getLocale() == 'en'  ? 'please wait to processing the goal' : 'يرجى الانتظار لمعالجة الهدف';
            }
        } else {
            $message = app()->getLocale() == 'en'  ? 'This plan is not available to you or is not intended for food.😊' : 'هذه الخطة غير متاحة لك وليست مخصصة للطعام.😊';
        }

        return response()->json(['type' => $data, 'message' => $message]);
    }
    public function storeSleep(StoreTargetSleepRequest $request)
    {
        $goal_id = Target::where('user_id', auth()->id())->WhereHas('goalPlan', function ($q) {
            $q->where('plan_id', 14);
        })->with('goalPLan')->first();
        $message = '';
        $data = 'error';
        $currentDate = Carbon::now();
        if ($goal_id) {
            $countActive = Target::where('user_id', auth()->id())->WhereHas('goalPlan', function ($q) {
                $q->where('plan_id', 14);
            })->where('active', 1)->count();
            if ($countActive) {
                $countDay = Target::where('user_id', auth()->id())->where('goal_plan_id', $goal_id->goal_plan_id)->whereDate('created_at', $currentDate)->get();
                if (count($countDay)) {
                    Target::where('user_id', auth()->id())->where('goal_plan_id', $goal_id->goal_plan_id)->whereDate('created_at', $currentDate)->delete();
                }
                Target::create([
                    'user_id' => auth()->id(),
                    'goal_plan_id' => $goal_id->goal_plan_id,
                    'sleep' => $request->hours,
                    'active' => true,
                ]);
                $observer = new GoalPlanObserver();
                $observer->update();
                $data = 'success';
                $message = app()->getLocale() == 'en'  ? 'Your progress in the plan sleep has been recorded. Keep going. 😎😍' : 'لقد تم تسجيل تقدمك في خطة النوم، استمر. 😎😍';
            } else {
                $message = app()->getLocale() == 'en'  ? 'please wait to processing the goal' : 'يرجى الانتظار لمعالجة الهدف';
            }
        } else {
            $message = app()->getLocale() == 'en' ? 'This plan is not available to you or is not intended for sleep.😊' : 'هذه الخطة غير متاحة لك وليست مخصصة للنوم.😊';
        }

        return response()->json(['type' => $data, 'message' => $message]);
    }
    public function storeWater(StoreTargetWaterRequest $request)
    {
        $goal_id = Target::where('user_id', auth()->id())->WhereHas('goalPlan', function ($q) {
            $q->where('plan_id', 15);
        })->with('goalPLan')->first();
        $data = 'error';
        $message = '';
        $currentDate = Carbon::now();
        if ($goal_id) {
            $countActive = Target::where('user_id', auth()->id())->WhereHas('goalPlan', function ($q) {
                $q->where('plan_id', 14);
            })->where('active', 1)->count();
            if ($countActive) {
                $countDay = Target::where('user_id', auth()->id())->where('goal_plan_id', $goal_id->goal_plan_id)->whereDate('created_at', $currentDate)->get();
                if (count($countDay)) {
                    Target::where('user_id', auth()->id())->where('goal_plan_id', $goal_id->goal_plan_id)->whereDate('created_at', $currentDate)->delete();
                }
                Target::create([
                    'user_id' => auth()->id(),
                    'goal_plan_id' => $goal_id->goal_plan_id,
                    'water' => $request->water,
                    'active' => true,
                ]);
                $observer = new GoalPlanObserver();
                $observer->update();
                $message = app()->getLocale() == 'en' ? 'Your progress in the drink water has been recorded. Keep going. 😎😍' : 'لقد تم تسجيل تقدمك في شرب الماء، استمر. 😎😍';
                $data = 'success';
            } else {
                $message = app()->getLocale() == 'en'  ? 'please wait to processing the goal' : 'يرجى الانتظار لمعالجة الهدف';
            }
        } else {
            $message = app()->getLocale() == 'en' ? 'This plan is not available to you or is not intended for water.😊' : 'هذه الخطة غير متاحة لك أو غير مخصصة للمياه.😊';
        }

        return response()->json(['type' => $data, 'message' => $message]);
    }
    public function storeE(StoreTargetRequest $request)
    {
        $currentDate = Carbon::now();
        $message = '';
        $data = 'error';
        $count = Target::where('user_id', auth()->id())->whereHas('goalPlan', function ($q) use ($request) {
            $q->where('plan_id', $request->plan_id);
        })
            ->whereHas('goalPlan.plan', function ($q) use ($request) {
                $q->where('type', '!=', 'sleep')->where('type', '!=', 'water');
            })
            ->count();
        if ($count) {
            $goal_plan_id = Target::whereHas('goalPlan', function ($q) use ($request) {
                $q->where('plan_id', $request->plan_id);
            })->where('user_id', auth()->id())->where('active', 1)->first();
            if ($goal_plan_id) {
                $check = Target::where('user_id', auth()->id())
                    ->where('goal_plan_id', $goal_plan_id->goal_plan_id)
                    ->whereDate('created_at', $currentDate)
                    ->where('check', $request->check)
                    ->first();
                if ($check) {
                    $check
                        ->update([
                            'user_id' => auth()->id(),
                            'goal_plan_id' => $goal_plan_id->goal_plan_id,
                            'calories' => $request->calories,
                            'check' > $check->check,
                            'active' => true
                        ]);
                } else {
                    Target::create([
                        'user_id' => auth()->id(),
                        'goal_plan_id' => $goal_plan_id->goal_plan_id,
                        'calories' => $request->calories,
                        'check' => $request->check,
                        'active' => true
                    ]);
                }
                $observer = new GoalPlanObserver();
                $observer->update();
                $message = app()->getLocale() == 'en'  ? 'Your progress in the exercises has been recorded. Keep going. 😎😍' : 'لقد تم تسجيل تقدمك في التمارين، استمر. 😎😍';
                $data = 'success';
            } else {
                $message = app()->getLocale() == 'en'  ? 'please wait to processing the goal' : 'يرجى الانتظار لمعالجة الهدف';
            }
        } else {
            $message = app()->getLocale() == 'en' ? 'This plan is not available to you or is not intended for exercise.😊'  : 'هذه الخطة غير متاحة لك وليست مخصصة لممارسة الرياضة.😊';
        }

        return response()->json(['type' => $data, 'message' => $message]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Target $target)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function addDay(UpdateTargetRequest $request, Target $target)
    {
        $dates = [];
        $dates_meal = [];
        $user = User::where('id', auth()->id())->first();
        $days = $user->days ? json_decode($user->days, true) : '{"sunday":true,"tuesday":true,"monday":true,"wednesday":true,"thrusday":true,"friday":true,"saturday":true}';
        $currentDate = Carbon::now();
        $acceptedCount = 0;

        while ($acceptedCount < 14) {
            $dayOfWeek = (int)$currentDate->format('w');

            $dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thrusday', 'friday', 'saturday'];
            $currentDayName = $dayNames[$dayOfWeek];

            if ($days[$currentDayName] === true) {
                $dates[] = $currentDate->format('Y-m-d');
                $acceptedCount++;
            }
            $dates_meal[] = $currentDate->format('Y-m-d');
            $currentDate->modify('+1 day');
        }

        // $checkAll = Target::where('user_id', auth()->id())->count();

        // $check = Target::where('user_id', auth()->id())->whereIn('goal_plan_id', $ids)->count();
        // if (!$check) {
        //     if (!$checkAll) {
        for ($i = 0; $i < 14; $i++) {
            Date::create([
                'user_id' => auth()->id(),
                'date' => $dates[$i],
                'date_meal' => $dates_meal[$i]
            ]);
        }
        $observer = new GoalPlanObserver();
        $observer->update();
        //     }
        // }

        Target::where('user_id', auth()->id())
            ->update([
                'active' => true
            ]);
        return response()->json(['data' => 'succ']);
    }

    public function update(UpdateTargetRequest $request, Target $target)
    {
        $dates = [];
        $dates_meal = [];
        $user = User::where('id', $request->user_id)->first();
        $days = $user->days ? json_decode($user->days, true) : '{"sunday":true,"tuesday":true,"monday":true,"wednesday":true,"thrusday":true,"friday":true,"saturday":true}';
        $currentDate = Carbon::now();
        $acceptedCount = 0;

        while ($acceptedCount < 14) {
            $dayOfWeek = (int)$currentDate->format('w');

            $dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thrusday', 'friday', 'saturday'];
            $currentDayName = $dayNames[$dayOfWeek];

            if ($days[$currentDayName] === true) {
                $dates[] = $currentDate->format('Y-m-d');
                $acceptedCount++;
            }
            $dates_meal[] = $currentDate->format('Y-m-d');
            $currentDate->modify('+1 day');
        }

        // $checkAll = Target::where('user_id', $request->user_id)->count();

        // $check = Target::where('user_id', $request->user_id)->whereIn('goal_plan_id', $ids)->count();
        // if (!$check) {
        //     if (!$checkAll) {
        for ($i = 0; $i < 14; $i++) {
            Date::create([
                'user_id' => $request->user_id,
                'date' => $dates[$i],
                'date_meal' => $dates_meal[$i]
            ]);
        }
        $observer = new GoalPlanObserver();
        $observer->update();
        //     }
        // }

        Target::where('user_id', $request->user_id)
            ->update([
                'active' => true
            ]);
        return response()->json(['data' => 'succ']);
    }
    public function notUpdate(UpdateTargetRequest $request, Target $target)
    {

        $user = User::where('id', $request->user_id)->first();
        if ($user) {
            $observer = new GoalPlanObserver();
            $observer->update();
            Target::where('user_id', $request->user_id)->delete();
            return response()->json(['data' => 'succ']);
        }
    }

    public function editScheduling(UpdateTargetRequest $request, Target $target)
    {
        $dates = [];
        $dates_meal = [];
        $type = 'error';
        $message = '';
        $start_day = User::where('id', auth()->id())->with('date')->first();
        $days = json_decode($request->days, true);
        if (count($start_day->date)) {
            $currentDate = Carbon::parse($start_day->date[0]->date_meal);
            $acceptedCount = 0;
            $request->validate([
                'days' => 'required',
            ]);
            $start_day->update([
                'days' => $request->days
            ]);

            while ($acceptedCount < 14) {
                $dayOfWeek = (int)$currentDate->format('w');

                $dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thrusday', 'friday', 'saturday'];
                $currentDayName = $dayNames[$dayOfWeek];

                if ($days[$currentDayName]) {
                    $dates[] = $currentDate->format('Y-m-d');
                    $acceptedCount++;
                }
                $dates_meal[] = $currentDate->format('Y-m-d');
                $currentDate->modify('+1 day');
            }
            Date::where('user_id', auth()->id())->delete();

            for ($i = 0; $i < 14; $i++) {
                Date::create([
                    'user_id' => auth()->id(),
                    'date' => $dates[$i],
                    'date_meal' => $dates_meal[$i]
                ]);
            }
            $observer = new GoalPlanObserver();
            $observer->update();
            $type = 'success';
            $message = app()->getLocale() == 'en' ? 'update your scheduling. 😎' : "لقد تم تعديل جدولك بنجاح 😎";
        } else {
            $message = app()->getLocale() == 'en' ? 'faild update your scheduling , plaese try agen' : "فشل تعديل جدولك حاول مرة اخرى";
        }

        return response()->json(['data' => $start_day, 'message' => $message, 'type' => $type]);
    }

    public function getRequestGoals()
    {
        $index = Target::selectRaw('user_id as id  , user_id as user_id, MAX(goal_plan_id) as goal_plan_id')->where('active', 0)->with(['users', 'goalPlan.goals'])->groupBy(['user_id'])->get();
        return response()->json(['data' => $index]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Target $target)
    {
        //
    }
}
