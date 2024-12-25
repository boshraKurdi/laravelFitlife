<?php

namespace App\Http\Controllers;

use App\Models\PlanLevel;
use App\Http\Requests\StorePlanLevelRequest;
use App\Http\Requests\UpdatePlanLevelRequest;
use App\Models\GoalPlanLevel;
use App\Models\Target;
use Carbon\Carbon;
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

    public function exercise(Request $request, $planLevel, $day, $week)
    {
        $arr = [];

        $arrDay = [];
        $Getdate = Target::where('user_id', auth()->id())->with('users.date')->first();
        $date = $Getdate->users->date;
        $firstWeek = [$date[0], $date[1], $date[2], $date[3], $date[4], $date[5], $date[6]];
        $secondWeek = [$date[7], $date[8], $date[9], $date[10], $date[11], $date[12], $date[13]];
        $today = Carbon::today();
        $count = Target::where('user_id', auth()->id())
            ->whereHas('goalPlanLevel', function ($q) use ($planLevel) {
                $q->where('plan_level_id', $planLevel);
            })->where('check', '!=', 0)
            ->count();
        $totalRate = intval(($count / 42) * 100);
        $countWeekOne = Target::where(function ($query) use ($firstWeek) {
            foreach ($firstWeek as $d) {
                $query->orWhereDate('updated_at', '=', Carbon::parse($d->date));
            }
        })->where('user_id', auth()->id())->whereHas('goalPlanLevel', function ($q) use ($planLevel) {
            $q->where('plan_level_id', $planLevel);
        })->where('check', '!=', 0)
            ->count();

        $totalRateWeekOne = intval(($countWeekOne / 21) * 100);
        $countWeekTwo = Target::where(function ($query) use ($secondWeek) {
            foreach ($secondWeek as $d) {
                $query->WhereDate('updated_at', '=', Carbon::parse($d->date));
            }
        })->where('user_id', auth()->id())->whereHas('goalPlanLevel', function ($q) use ($planLevel) {
            $q->where('plan_level_id', $planLevel);
        })->where('check', '!=', 0)
            ->count();

        $totalRateWeekTwo = intval(($countWeekTwo / 21) * 100);
        $countDay = Target::whereDate('updated_at', $today)->where('user_id', auth()->id())->whereHas('goalPlanLevel', function ($q) use ($planLevel) {
            $q->where('plan_level_id', $planLevel);
        })->where('check', '!=', 0)
            ->count();

        $totalRateDay = intval(($countDay / 3) * 100);
        if ($request->type) {
            if ($request->type === 'day') {
                if ($request->number_week === 'one') {
                    $arr = $firstWeek;
                } else if ($request->number_week === 'two') {
                    $arr = $secondWeek;
                }
                foreach ($arr as $d) {
                    $countDayTotal = Target::whereDate('updated_at', $d->date)->where('user_id', auth()->id())->whereHas('goalPlanLevel', function ($q) use ($planLevel) {
                        $q->where('plan_level_id', $planLevel);
                    })->where('check', '!=', 0)
                        ->count();
                    $totalRateDayTotal = intval(($countDayTotal / 3) * 100);
                    array_push($arrDay, ["$d->date" => $totalRateDayTotal]);
                }
            } else {
                array_push($arrDay, ["first week" => $totalRateWeekOne]);
                array_push($arrDay, ["scound week" => $totalRateWeekTwo]);
            }
        }
        //get exe
        $exe =  PlanLevel::where('id', $planLevel)->with(['exercise' => function ($q) use ($day, $week) {
            $q->where('day', $day)->where('week', $week);
        }, 'exercise.media', 'targets' => function ($q) {
            $q->where('user_id', auth()->id())->where('check', '!=', 0);
        }, 'targets.users', 'targets.users.date'])->first();
        $exe->totalRate =   $totalRate;
        $exe->totalRateDay =   $totalRateDay;
        $exe->totalRateWeekOne =   $totalRateWeekOne;
        $exe->totalRateWeekTwo =   $totalRateWeekTwo;
        $exe->date =   $date;
        $exe->arrDay = $arrDay;

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
            $count = Target::where('user_id', auth()->id())
                ->whereHas('goalPlanLevel', function ($q) use ($i) {
                    $q->where('plan_level_id', $i->id);
                })->where('check', '!=', 0)
                ->count();
            $totalRate = intval(($count / 42) * 100);
            $i->totalRate = $totalRate;
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
    public function show(Request $request, $id)
    {
        $arr = [];

        $arrDay = [];
        $Getdate = Target::where('user_id', auth()->id())->with('users.date')->first();
        $date = $Getdate->users->date;
        $firstWeek = [$date[0], $date[1], $date[2], $date[3], $date[4], $date[5], $date[6]];
        $secondWeek = [$date[7], $date[8], $date[9], $date[10], $date[11], $date[12], $date[13]];
        $today = Carbon::today();
        $count = Target::where('user_id', auth()->id())
            ->whereHas('goalPlanLevel', function ($q) use ($id) {
                $q->where('plan_level_id', $id);
            })->where('check', '!=', 0)
            ->count();
        $totalRate = intval(($count / 42) * 100);
        $countWeekOne = Target::where(function ($query) use ($firstWeek) {
            foreach ($firstWeek as $d) {
                $query->orWhereDate('updated_at', '=', Carbon::parse($d->date));
            }
        })->where('user_id', auth()->id())->whereHas('goalPlanLevel', function ($q) use ($id) {
            $q->where('plan_level_id', $id);
        })->where('check', '!=', 0)
            ->count();

        $totalRateWeekOne = intval(($countWeekOne / 21) * 100);
        $countWeekTwo = Target::where(function ($query) use ($secondWeek) {
            foreach ($secondWeek as $d) {
                $query->WhereDate('updated_at', '=', Carbon::parse($d->date));
            }
        })->where('user_id', auth()->id())->whereHas('goalPlanLevel', function ($q) use ($id) {
            $q->where('plan_level_id', $id);
        })->where('check', '!=', 0)
            ->count();

        $totalRateWeekTwo = intval(($countWeekTwo / 21) * 100);
        $countDay = Target::whereDate('updated_at', $today)->where('user_id', auth()->id())->whereHas('goalPlanLevel', function ($q) use ($id) {
            $q->where('plan_level_id', $id);
        })->where('check', '!=', 0)
            ->count();

        $totalRateDay = intval(($countDay / 3) * 100);
        if ($request->type) {
            if ($request->type === 'day') {
                if ($request->number_week === 'one') {
                    $arr = $firstWeek;
                } else if ($request->number_week === 'two') {
                    $arr = $secondWeek;
                }
                foreach ($arr as $d) {
                    $countDayTotal = Target::whereDate('updated_at', $d->date)->where('user_id', auth()->id())->whereHas('goalPlanLevel', function ($q) use ($id) {
                        $q->where('plan_level_id', $id);
                    })->where('check', '!=', 0)
                        ->count();
                    $totalRateDayTotal = intval(($countDayTotal / 3) * 100);
                    array_push($arrDay, ["$d->date" => $totalRateDayTotal]);
                }
            } else {
                array_push($arrDay, ["first week" => $totalRateWeekOne]);
                array_push($arrDay, ["scound week" => $totalRateWeekTwo]);
            }
        }
        $show = PlanLevel::where('id', $id)
            ->with(['targets.users' => function ($q) use ($today) {
                $q->where('id', auth()->id())->whereDate('updated_at', $today);
            }, 'targets.users.date', 'plan', 'plan.media', 'level'])
            ->first();
        $show->date =   $date;
        $show->totalRate =   $totalRate;
        $show->totalRateDay =   $totalRateDay;
        $show->totalRateWeekOne =   $totalRateWeekOne;
        $show->totalRateWeekTwo =   $totalRateWeekTwo;
        $show->arrDay = $arrDay;
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
