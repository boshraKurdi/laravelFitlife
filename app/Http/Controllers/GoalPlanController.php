<?php

namespace App\Http\Controllers;

use App\Models\GoalPlan;
use App\Http\Requests\StoreGoalPlanRequest;
use App\Http\Requests\UpdateGoalPlanRequest;
use App\Models\Date;
use App\Models\Target;
use App\Models\Update;
use App\Observers\GoalPlanObserver;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPSTORM_META\type;

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
        $type = 'success';
        $message = '';
        $CountGetdate = Target::where('user_id', auth()->id())->where('active', '!=', 2)->count();
        if ($CountGetdate) {
            $CountGetdateActive = Target::where('user_id', auth()->id())->where('active', 1)->count();
            if ($CountGetdateActive) {
                $Getdate = Target::where('user_id', auth()->id())->whereHas('goalPlan', function ($q) use ($id) {
                    $q->where('goal_id', $id);
                })->with('users.date')->first();
                if (!$Getdate) {
                    $message = 'Finish your first goal and then you can start another one to be the person you want to be ✌️👊';
                    $type = 'error';
                }
                $date = $Getdate ? $Getdate->users->date : [];
            } else {
                $message = 'please wait to processing the goal';
                $type = 'error';
            }
        } else {
            $message = "If you want to see more details please register with this goal and don't forget to check your email address 😉😉";
            $type = 'error';
        }
        $targets = GoalPlan::query()->where('goal_id', $id)->whereHas('plan', function ($q) {
            $q->where('type', '!=', 'food')->where('type', '!=', 'sleep')->where('type', '!=', 'water');
        })->with(['plan', 'plan.media', 'goals'])->get();
        return response()->json(['data' => $targets, 'date' => $date, 'message' => $message, 'type' => $type]);
    }


    public function getPlanForGoalsWithMuscle(Request $request)
    {
        $muscleGroups = ['thigh exercises', 'Abdominal exercises', 'Stretching exercises', 'Sculpting exercises'];
        $targets = array();
        $message = '';
        $type = 'error';
        $CountGetdate = Target::where('user_id', auth()->id())->where('active', '!=', 2)->count();
        if ($CountGetdate) {
            $target = GoalPlan::whereHas('targets', function ($q) {
                $q->where('active', 1)->where('user_id', auth()->id());
            })
                ->first();
            if ($target) {

                foreach ($muscleGroups as $muscle) {
                    $r = GoalPlan::where('goal_id', $target->goal_id)->whereHas('plan', function ($q) use ($muscle) {
                        $q->where('type', $muscle);
                    })
                        ->with(['plan', 'plan.media', 'goals'])->get();
                    count($r) ?  array_push($targets, $r) : '';
                }
                $type = 'success';
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = "If you want to see more details please register with this goal and don't forget to check your email address 😉😉";
        }



        return response()->json(['data' => $targets, 'type' => $type, 'message' => $message]);
    }

    public function getPlanForGoalsWithMuscleL(Request $request)
    {
        $muscleGroups = [
            'thigh_exercises' => 'thigh exercises',
            'abdominal_exercises' => 'Abdominal exercises',
            'stretching_exercises' => 'Stretching exercises',
            'sculpting_exercises' => 'Sculpting exercises'
        ];

        $targets = [];
        $message = '';
        $type = 'error';

        $CountGetdate = Target::where('user_id', auth()->id())->where('active', '!=', 2)->count();

        if ($CountGetdate) {
            $target = GoalPlan::whereHas('targets', function ($q) {
                $q->where('active', 1)->where('user_id', auth()->id());
            })->first();

            if ($target) {
                foreach ($muscleGroups as $key => $muscle) {
                    $plans = GoalPlan::where('goal_id', $target->goal_id)
                        ->whereHas('plan', function ($q) use ($muscle) {
                            $q->where('type', $muscle);
                        })
                        ->with(['plan', 'plan.media', 'goals'])
                        ->get();

                    if (count($plans)) {
                        $targets[$key] = $plans;
                    }
                }
                $type = 'success';
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = "If you want to see more details please register with this goal and don't forget to check your email address 😉😉";
        }

        return response()->json(['data' => $targets, 'type' => $type, 'message' => $message]);
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
        $type = 'error';

        $GoalPlan = GoalPlan::where('goal_id', $id)->get();
        foreach ($GoalPlan as $g) {
            array_push($ids, $g->id);
        }
        $checkAll = Target::where('user_id', auth()->id())->where('active', "!=", 2)->count();
        $check = Target::where('user_id', auth()->id())->where('active', '!=', 2)->whereIn('goal_plan_id', $ids)->count();
        $message = app()->getLocale() == 'en' ? 'you have already registered for this goal.😊' : 'لقد قمت بالتسجيل بالفعل لهذا الهدف.😊';
        if (!$check) {
            if (!$checkAll) {
                foreach ($GoalPlan as $goal) {
                    $goal->users()->attach(auth()->id());
                }
                $observer = new GoalPlanObserver();
                $observer->update();
                $message = app()->getLocale() == 'en'  ? 'Your journey has just started, one mile journey starts with one step 🤩🤩' : 'رحلتك بدأت للتو، رحلة الميل الواحد تبدأ بخطوة واحدة 🤩🤩';
                $type = 'success';
            }
        }


        return response()->json([
            'data' => $message,
            'type' => $type,
        ]);
    }

    public function getDateGoal()
    {
        $date = '';
        $check = Target::where('user_id', auth()->id())->where('active', '!=', 2)->get();
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
