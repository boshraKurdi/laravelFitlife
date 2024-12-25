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
        $count = Target::where('user_id', auth()->id())->where('goal_plan_level_id', $request->goal_plan_level_id)->count();
        $rate = intval(($count / 14) * 100);
        $today = Carbon::today();
        $UpdatedToday = Target::whereDate('updated_at', $today)->where('user_id', auth()->id())->where('goal_plan_level_id', $request->goal_plan_level_id)->get();
        $target = Target::create([
            'user_id' => auth()->id(),
            'goal_plan_level_id' => $request->goal_plan_level_id,
            'calories' => $request->calories,
            'check' => $request->check,
            'active' => true
        ]);
        return response()->json($target);
    }
    public function storeE(StoreTargetRequest $request)
    {
        $currentDate = Carbon::now();
        $goal_plan_level_id = Target::whereHas('goalPlanLevel', function ($q) use ($request) {
            $q->where('goal_plan_levels.plan_level_id', $request->plan_level_id);
        })->where('user_id', auth()->id())->first();

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
            $target = 'the user is not shear in this plan';
        }
        return response()->json($target);
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
