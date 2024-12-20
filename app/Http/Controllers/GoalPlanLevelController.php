<?php

namespace App\Http\Controllers;

use App\Models\GoalPlanLevel;
use App\Http\Requests\StoreGoalPlanLevelRequest;
use App\Http\Requests\UpdateGoalPlanLevelRequest;
use Illuminate\Http\Request;

class GoalPlanLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function getPlanForGoals($id)
    {
        $targets = GoalPlanLevel::query()->where('goal_id', $id)->whereHas('planLevels.plan', function ($q) {
            $q->where('type', '!=', 'food');
        })->with(['planLevels.plan', 'planLevels.level', 'planLevels.plan.media', 'goals'])->get();
        return response()->json(['data' => $targets]);
    }


    public function getPlanForGoalsWithMuscle(Request $request)
    {
        $muscleGroups = ['thigh exercises', 'Abdominal exercises', 'Stretching exercises', 'Sculpting exercises'];
        $targets = array();
        $target = GoalPlanLevel::whereHas('users', function ($q) {
            $q->where('user_id', auth()->id());
        })->whereHas('targets', function ($q) {
            $q->where('active', true);
        })->count();
        if ($target) {
            if ($request->id) {
                foreach ($muscleGroups as $muscle) {
                    $r = GoalPlanLevel::query()->where('goal_id', $request->id)->whereHas('planLevels.plan', function ($q) use ($muscle) {
                        $q->where('type', $muscle);
                    })
                        ->with(['planLevels.plan', 'planLevels.level', 'planLevels.plan.media', 'goals'])->get();
                    array_push($targets, $r);
                }
            }
        } else {
            $targets = 'please wait to processing the goal';
        }


        return response()->json(['data' => $targets]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGoalPlanLevelRequest $request)
    {
        return response()->json('s');
    }
    public function insert($id)
    {
        $goalPlanLevel = GoalPlanLevel::where('goal_id', $id)->get();
        foreach ($goalPlanLevel as $goal) {
            $goal->users()->attach(auth()->id());
        }
        return response()->json(['data' => 'succ']);
    }

    /**
     * Display the specified resource.
     */
    public function show(GoalPlanLevel $goalPlanLevel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGoalPlanLevelRequest $request, GoalPlanLevel $goalPlanLevel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GoalPlanLevel $goalPlanLevel)
    {
        //
    }
}
