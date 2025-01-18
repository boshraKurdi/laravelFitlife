<?php

namespace App\Http\Controllers;

use App\Models\Target;
use App\Http\Requests\StoreTargetRequest;
use App\Http\Requests\StoreTargetSleepRequest;
use App\Http\Requests\StoreTargetWaterRequest;
use App\Http\Requests\UpdateTargetRequest;
use App\Models\GoalPlan;
use App\Models\User;
use App\Observers\GoalPlanObserver;
use Carbon\Carbon;

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
                    $message = 'Your progress in the plan meals has been recorded. Keep going. 😎😍';
                }
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = 'This plan is not available to you or is not intended for food.😊';
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
                $message = 'Your progress in the plan sleep has been recorded. Keep going. 😎😍';
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = 'This plan is not available to you or is not intended for sleep.😊';
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
                $message = 'Your progress in the drink water has been recorded. Keep going. 😎😍';
                $data = 'success';
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = 'This plan is not available to you or is not intended for water.😊';
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
        })->count();
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
                $message = 'Your progress in the exercises has been recorded. Keep going. 😎😍';
                $data = 'success';
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = 'This plan is not available to you or is not intended for exercise.😊';
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
    public function update(UpdateTargetRequest $request, Target $target)
    {
        $ids = [];
        $goal_plan_id = GoalPlan::where('goal_id', $request->goal_id)->get();
        foreach ($goal_plan_id as $id) {
            array_push($ids, $id->id);
        }
        Target::whereIn('goal_plan_id', $ids)->where('user_id', $request->user_id)
            ->update([
                'active' => true
            ]);
        return response()->json(['data' => 'succ']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Target $target)
    {
        //
    }
}
