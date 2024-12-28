<?php

namespace App\Http\Controllers;

use App\Models\Target;
use App\Http\Requests\StoreTargetRequest;
use App\Http\Requests\UpdateTargetRequest;
use App\Models\GoalPlanLevel;
use App\Models\User;
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
        $message = '';
        $target = [];
        $count = Target::where('user_id', auth()->id())->where('goal_plan_level_id', $request->goal_plan_level_id)->count();
        if ($count) {
            $countActive = Target::where('user_id', auth()->id())->where('goal_plan_level_id', $request->goal_plan_level_id)->where('active', 1)->count();
            if ($countActive) {
                $target = Target::create([
                    'user_id' => auth()->id(),
                    'goal_plan_level_id' => $request->goal_plan_level_id,
                    'calories' => $request->calories,
                    'check' => $request->check,
                    'active' => true
                ]);
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = 'You are not involved in a goal';
        }

        return response()->json(['data' => $target, 'message' => $message]);
    }
    public function storeE(StoreTargetRequest $request)
    {
        $currentDate = Carbon::now();
        $message = '';
        $target = [];
        $goal_plan_level_id = Target::whereHas('goalPlanLevel', function ($q) use ($request) {
            $q->where('goal_plan_levels.plan_level_id', $request->plan_level_id);
        })->where('user_id', auth()->id())->where('active', 1)->first();
        $count = Target::where('user_id', auth()->id())->count();
        if ($count) {
            if ($goal_plan_level_id) {
                $check = Target::where('user_id', auth()->id())
                    ->where('goal_plan_level_id', $goal_plan_level_id->goal_plan_level_id)
                    ->whereDate('created_at', $currentDate)
                    ->where('check', $request->check)
                    ->first();
                if ($check) {
                    $target =
                        $check
                        ->update([
                            'user_id' => auth()->id(),
                            'goal_plan_level_id' => $goal_plan_level_id->goal_plan_level_id,
                            'calories' => $request->calories + $check->calories,
                            'check' > $check->check,
                            'active' => true
                        ]);
                } else {
                    $target = Target::create([
                        'user_id' => auth()->id(),
                        'goal_plan_level_id' => $goal_plan_level_id->goal_plan_level_id,
                        'calories' => $request->calories,
                        'check' => $request->check,
                        'active' => true
                    ]);
                }
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = 'You are not involved in a goal';
        }

        return response()->json(['data' => $target, 'message' => $message]);
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
        $goal_plan_level_id = GoalPlanLevel::where('goal_id', $request->goal_id)->get();
        foreach ($goal_plan_level_id as $id) {
            array_push($ids, $id->id);
        }
        Target::whereIn('goal_plan_level_id', $ids)->where('user_id', $request->user_id)
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
