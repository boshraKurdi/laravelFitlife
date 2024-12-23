<?php

namespace App\Http\Controllers;

use App\Models\PlanLevel;
use App\Http\Requests\StorePlanLevelRequest;
use App\Http\Requests\UpdatePlanLevelRequest;
use App\Models\GoalPlanLevel;
use App\Models\Target;
use Illuminate\Http\Request;

class PlanLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $muscleGroups = ['arm', 'pectoral', 'belly', 'thigh'];
        $results = array();

        foreach ($muscleGroups as $muscle) {
            $r = PlanLevel::whereHas('plan', function ($q) use ($muscle) {
                $q->where('muscle', $muscle);
            })
                ->with(['plan', 'level', 'plan.media'])->get();
            array_push($results, $r);
        }

        return response()->json(['data' => $results]);
    }
    public function getPlans()
    {
        $data = PlanLevel::with(['plan', 'level', 'plan.media'])->get();
        return response()->json(['data' => $data]);
    }

    public function exercise($planLevel, $day, $week)
    {
        $exe =  PlanLevel::where('id', $planLevel)->with(['exercise' => function ($q) use ($day, $week) {
            $q->where('day', $day)->where('week', $week);
        }, 'exercise.media'])->get();
        return response()->json(['data' => $exe]);
    }
    public function getExerciseForPlan($planLevel)
    {
        $exe =  PlanLevel::where('id', $planLevel)->with(['exercise', 'exercise.media'])->get();
        return response()->json(['data' => $exe]);
    }

    public function meal($day, $week, Request $request)
    {
        $exe = '';
        $target = GoalPlanLevel::whereHas('users', function ($q) {
            $q->where('user_id', auth()->id());
        })->whereHas('targets', function ($q) {
            $q->where('active', true);
        })->get();
        if (count($target)) {
            $plan_id = PlanLevel::whereHas('goals', function ($q) use ($target) {
                $q->where('goals.id', $target[0]->goal_id);
            })->whereHas('plan', function ($q) {
                $q->where('type', 'food');
            })->get();
            if ($request->breakfast) {
                $exe =  PlanLevel::where('id', $plan_id[0]->id)->with(['meal' => function ($q) use ($day, $week) {
                    $q->where('day', $day)->where('week', $week)->where('breakfast', 1);
                }, 'meal.media'])->get();
            } else if ($request->lunch) {
                $exe =  PlanLevel::where('id', $plan_id[0]->id)->with(['meal' => function ($q) use ($day, $week) {
                    $q->where('day', $day)->where('week', $week)->where('lunch', 1);
                }, 'meal.media'])->get();
            } else {
                $exe =  PlanLevel::where('id', $plan_id[0]->id)->with(['meal' => function ($q) use ($day, $week) {
                    $q->where('day', $day)->where('week', $week)->where('dinner', 1);
                }, 'meal.media'])->get();
            }
        } else {
            $exe = 'please wait to processing the goal';
        }

        return response()->json([
            'data' => $exe,
            'id' => $plan_id[0]->id
        ]);
    }

    public function getUserPlans()
    {
        $plans = PlanLevel::query()
            ->whereHas('targets', function ($q) {
                $q->where('user_id', auth()->id());
            })->whereHas('plan', function ($q) {
                $q->where('type', '!=', 'food');
            })
            ->with(['targets' => function ($query) {
                $query->where('user_id', auth()->id());
            }, 'plan', 'plan.media', 'level', 'targets.goalPlanLevel'])
            ->get();
        $newIndex = $plans->map(function ($i) {
            $i->myTarget = $i->targets->last();
            return $i;
        });
        return response()->json(['data' => $newIndex]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlanLevelRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $show = PlanLevel::where('id', $id)->whereHas('targets', function ($q) {
            $q->where('user_id', auth()->id());
        })
            ->with(['targets' => function ($query) {
                $query->where('user_id', auth()->id());
            }, 'targets.check', 'plan', 'plan.media', 'level'])
            ->first();
        $show->Mytargets = $show->targets->last();
        return response()->json($show);
    }

    public function showPlan(PlanLevel $planLevel)
    {
        $i = $planLevel->load(['plan', 'plan.media',  'level', 'exercise', 'exercise.media']);
        return response()->json($i);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlanLevelRequest $request, PlanLevel $planLevel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlanLevel $planLevel)
    {
        $planLevel->delete();
        return response()->json('planLevel been deleted successfully');
    }
}
