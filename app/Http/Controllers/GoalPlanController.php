<?php

namespace App\Http\Controllers;

use App\Models\GoalPlan;
use App\Http\Requests\StoreGoalPlanRequest;
use App\Http\Requests\UpdateGoalPlanRequest;
use App\Models\Date;
use App\Models\Target;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalPlanController extends Controller
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
        $date = [];
        $message = '';
        $CountGetdate = Target::where('user_id', auth()->id())->count();
        if ($CountGetdate) {
            $CountGetdateActive = Target::where('user_id', auth()->id())->where('active', 1)->count();
            if ($CountGetdateActive) {
                $Getdate = Target::where('user_id', auth()->id())->with('users.date')->first();
                $date = $Getdate->users->date;
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = 'You are not involved in a goal';
        }
        $targets = GoalPlan::query()->where('goal_id', $id)->whereHas('plan', function ($q) {
            $q->where('type', '!=', 'food');
        })->with(['plan', 'plan.media', 'goals'])->get();

        return response()->json(['data' => $targets, 'date' => $date, 'message' => $message]);
    }


    public function getPlanForGoalsWithMuscle(Request $request)
    {
        $muscleGroups = ['thigh exercises', 'Abdominal exercises', 'Stretching exercises', 'Sculpting exercises'];
        $targets = array();
        $message = '';
        $CountGetdate = Target::where('user_id', auth()->id())->count();
        if ($CountGetdate) {
            $check = GoalPlan::whereHas('users', function ($q) {
                $q->where('user_id', auth()->id());
            })->count();
            $target = GoalPlan::whereHas('targets', function ($q) {
                $q->where('active', 1)->where('user_id', auth()->id());
            })
                ->count();
            if ($check) {
                if ($target) {
                    if ($request->id) {
                        foreach ($muscleGroups as $muscle) {
                            $r = GoalPlan::query()->where('goal_id', $request->id)->whereHas('plan', function ($q) use ($muscle) {
                                $q->where('type', $muscle);
                            })
                                ->with(['plan', 'plan.media', 'goals'])->get();
                            array_push($targets, $r);
                        }
                    }
                } else {
                    $message = 'please wait to processing the goal';
                }
            }
        } else {
            $message = 'You are not involved in a goal';
        }



        return response()->json(['data' => $targets, 'message' => $message]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGoalPlanRequest $request)
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
        $GoalPlan = GoalPlan::where('goal_id', $id)->get();
        foreach ($GoalPlan as $g) {
            array_push($ids, $g->id);
        }
        $check = Target::where('user_id', auth()->id())->whereIn('goal_plan_id', $ids)->count();
        if (!$check) {
            foreach ($GoalPlan as $goal) {
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
    public function show(GoalPlan $goalPlan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGoalPlanRequest $request, GoalPlan $goalPlan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GoalPlan $goalPlan)
    {
        //
    }
}
