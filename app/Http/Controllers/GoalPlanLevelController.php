<?php

namespace App\Http\Controllers;

use App\Models\GoalPlanLevel;
use App\Http\Requests\StoreGoalPlanLevelRequest;
use App\Http\Requests\UpdateGoalPlanLevelRequest;
use App\Http\Resources\PlanLevelResources;
use App\Models\Date;
use App\Models\Target;
use Carbon\Carbon;
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
        $Getdate = Target::where('user_id', auth()->id())->with('users.date')->first();
        $date = $Getdate->users->date;
        $targets = GoalPlanLevel::query()->where('goal_id', $id)->whereHas('planLevels.plan', function ($q) {
            $q->where('type', '!=', 'food');
        })->whereHas('users', function ($q) {
            $q->where('user_id', auth()->id());
        })->with(['planLevels', 'planLevels.plan', 'planLevels.level', 'planLevels.plan.media', 'goals'])->get();

        return response()->json(['data' => $targets, 'date' => $date]);
    }


    public function getPlanForGoalsWithMuscle(Request $request)
    {
        $muscleGroups = ['thigh exercises', 'Abdominal exercises', 'Stretching exercises', 'Sculpting exercises'];
        $targets = array();
        $check = GoalPlanLevel::whereHas('users', function ($q) {
            $q->where('user_id', auth()->id());
        })->count();
        $target = GoalPlanLevel::whereHas('targets', function ($q) {
            $q->where('active', 1)->where('user_id', auth()->id());
        })
            ->count();
        if ($check) {
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
        $ids = [];
        $dates = [];
        $currentDate = Carbon::now();
        for ($i = 0; $i <= 14; $i++) {
            $dates[] = $currentDate->copy()->addDays($i)->format('Y-m-d');
        }
        $goalPlanLevel = GoalPlanLevel::where('goal_id', $id)->get();
        foreach ($goalPlanLevel as $g) {
            array_push($ids, $g->id);
        }
        $check = Target::where('user_id', auth()->id())->whereIn('goal_plan_level_id', $ids)->count();
        if (!$check) {
            foreach ($goalPlanLevel as $goal) {
                $goal->users()->attach(auth()->id());
            }
            foreach ($dates as $d) {
                Date::create([
                    'user_id' => auth()->id(),
                    'date' => $d
                ]);
            }
        }

        return response()->json([
            'data' => 'succ',
        ]);
    }

    public function getDateGoal()
    {
        $date = '';
        $check = Target::where('user_id', auth()->id())->get();
        if (count($check)) {
            $date = $check[0]->created_at;
        }

        return response()->json([
            'data' => $date,
            'count' => count($check)
        ]);
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
