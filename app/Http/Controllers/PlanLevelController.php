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

        $x = [0];
        $y = [0];
        $date = [];
        $message = '';
        $exe  = [];
        $firstWeek = [];
        $secondWeek = [];
        $CountGetdate = Target::where('user_id', auth()->id())->whereHas('goalPlanLevel', function ($q) use ($planLevel) {
            $q->where('plan_level_id', $planLevel);
        })->count();
        if ($CountGetdate) {
            $CountGetdateActive = Target::where('user_id', auth()->id())->where('active', 1)->count();
            if ($CountGetdateActive) {
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
                    if ($request->type === 'weekly') {
                        if ($request->number_week == 1) {
                            $arr = $firstWeek;
                        } else if ($request->number_week == 2) {
                            $arr = $secondWeek;
                        }
                        foreach ($arr as $d) {
                            $countDayTotal = Target::whereDate('updated_at', $d->date)->where('user_id', auth()->id())->whereHas('goalPlanLevel', function ($q) use ($planLevel) {
                                $q->where('plan_level_id', $planLevel);
                            })->where('check', '!=', 0)
                                ->count();
                            $totalRateDayTotal = intval(($countDayTotal / 3) * 100);
                            array_push($x,  $d->date);
                            array_push($y, $totalRateDayTotal);
                        }
                    } else {
                        array_push($x, 1);
                        array_push($y, $totalRateWeekOne);
                        array_push($x, 2);
                        array_push($y, $totalRateWeekTwo);
                    }
                }
                //get exe
                $exe =  PlanLevel::where('id', $planLevel)->with(['exercise' => function ($q) use ($day, $week) {
                    $q->where('day', $day)->where('week', $week);
                }, 'exercise.media', 'targets' => function ($q) use ($today) {
                    $q->where('user_id', auth()->id())->where('check', '!=', 0)->whereDate('targets.created_at', $today);
                }, 'targets.users', 'targets.users.date'])->first();
                $exe->totalRate =   $totalRate;
                $exe->totalRateDay =   $totalRateDay;
                $exe->totalRateWeekOne =   $totalRateWeekOne;
                $exe->totalRateWeekTwo =   $totalRateWeekTwo;
                $exe->x = $x;
                $exe->y = $y;
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = 'You are not involved in a goal';
        }

        return response()->json(['data' => $exe, 'message' => $message]);
    }
    public function getExerciseForPlan($planLevel)
    {
        $exe =  PlanLevel::where('id', $planLevel)->with(['exercise', 'exercise.media'])->get();
        return response()->json(['data' => $exe]);
    }

    public function meal($day, $week, Request $request)
    {
        $exe = [];
        $message = '';
        $planId = 0;
        $check = Target::where('user_id', auth()->id())->count();
        if ($check) {
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
                $planId = $plan_id[0]->id;
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
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = 'You are not involved in a goal';
        }


        return response()->json([
            'data' => $exe,
            'id' => $planId,
            'message' => $message
        ]);
    }

    public function getUserPlans()
    {
        $message = '';
        $newIndex = [];
        $CountGetdate = Target::where('user_id', auth()->id())->count();
        if ($CountGetdate) {
            $CountGetdateWithActive = Target::where('user_id', auth()->id())->where('active', 1)->count();
            if ($CountGetdateWithActive) {
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
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = 'You are not involved in a goal';
        }

        return response()->json(['data' => $newIndex, 'message' => $message]);
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
        $date = [];
        $message = '';
        $show = [];
        $CountGetdate = Target::where('user_id', auth()->id())->count();
        if ($CountGetdate) {
            $CountGetdateActive = Target::where('user_id', auth()->id())->where('active', 1)->count();
            if ($CountGetdateActive) {
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
                    array_push($arrDay, ['x' => 0, 'y' => 0]);
                    if ($request->type === 'weekly') {
                        if ($request->number_week == 1) {
                            $arr = $firstWeek;
                        } else if ($request->number_week == 2) {
                            $arr = $secondWeek;
                        }
                        foreach ($arr as $d) {
                            $countDayTotal = Target::whereDate('updated_at', $d->date)->where('user_id', auth()->id())->whereHas('goalPlanLevel', function ($q) use ($id) {
                                $q->where('plan_level_id', $id);
                            })->where('check', '!=', 0)
                                ->count();
                            $totalRateDayTotal = intval(($countDayTotal / 3) * 100);
                            array_push($arrDay, ['x' => $d->date, 'y' => $totalRateDayTotal]);
                        }
                    } else {
                        array_push($arrDay, ["x" => 'first week', 'y' => $totalRateWeekOne]);
                        array_push($arrDay, ['x' => "scound week", 'y' => $totalRateWeekTwo]);
                    }
                }
                $show = PlanLevel::where('id', $id)
                    ->with(['targets' => function ($q) use ($today) {
                        $q->where('user_id', auth()->id())->whereDate('targets.created_at', $today)->where('check', '!=', 0);
                    }, 'targets.users', 'targets.users.date', 'plan', 'plan.media', 'level'])
                    ->first();
                $show->date =   $date;
                $show->totalRate =   $totalRate;
                $show->totalRateDay =   $totalRateDay;
                $show->totalRateWeekOne =   $totalRateWeekOne;
                $show->totalRateWeekTwo =   $totalRateWeekTwo;
                $show->arrDay = $arrDay;
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = 'You are not involved in a goal';
        }
        return response()->json(['data' => $show, 'message' => $message]);
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
