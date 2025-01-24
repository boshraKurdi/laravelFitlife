<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetMealRequest;
use App\Models\Plan;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\GoalPlan;
use Illuminate\Support\Facades\Auth;
use App\Models\Target;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $index = Plan::with(['media'])->get();
        return response()->json(['data' => $index]);
    }

    public function getPlanForGoals($ids)
    {
        $plans = Plan::query()->whereHas('goalPlan', function ($q)  use ($ids) {
            $q->where('goal_id', $ids);
        })
            // ->with(['levels', 'goalPlanLevel' => function ($q) use ($ids) {
            //     $q->where('goal_id', $ids);
            // }])
            ->get();

        return response()->json($plans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlanRequest $request)
    {
        $plan = Plan::query()->create([
            'title' => $request->title,
            'description' => $request->description,
            'muscle' => $request->muscle,
            'duration' => $request->duration
        ]);
        if ($request->media) {
            $plan->addMediaFromRequest('media')->toMediaCollection('plans');
        }
        return response()->json($plan);
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan)
    {
        $show = $plan->load(['media']);
        return response()->json($show);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        $update = $plan->update([
            'title' => $request->title,
            'title_ar' => $request->title_ar,
            'description' => $request->description,
            'description_ar' => $request->description_ar,
            'muscle' => $request->muscle,
            'muscle_ar' => $request->muscle_ar,
            'duration' => $request->duration
        ]);
        if ($request->media) {
            $plan->addMediaFromRequest('media')->toMediaCollection('plans');
        }
        return response()->json(['data' => 'update successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        //
    }

    public function exercise(Request $request, $plan, $day, $week)
    {
        $arr = [];


        $x = [0];
        $y = [0];
        $date = [];
        $message = '';
        $exe  = [];
        $type = "error";
        $firstWeek = [];
        $secondWeek = [];
        $CountGetdate = Target::where('user_id', auth()->id())->whereHas('goalPlan', function ($q) use ($plan) {
            $q->where('plan_id', $plan);
        })->count();
        if ($CountGetdate) {
            $CountGetdateActive = Target::where('user_id', auth()->id())->where('active', 1)->count();
            if ($CountGetdateActive) {
                $Getdate = Target::where('user_id', auth()->id())->with('users.date')->first();
                $date = $Getdate->users->date;
                $firstWeek = [$date[0], $date[1], $date[2], $date[3], $date[4], $date[5], $date[6]];
                $secondWeek = [$date[7], $date[8], $date[9], $date[10], $date[11], $date[12], $date[13]];
                $today = Carbon::today();
                //get count target plan and user
                $count = Target::where('user_id', auth()->id())
                    ->whereHas('goalPlan', function ($q) use ($plan) {
                        $q->where('plan_id', $plan);
                    })->where('check', '!=', 0)->count();
                $totalRate = intval(($count / 42) * 100);
                $countWeekOne = Target::where('user_id', auth()->id())
                    ->whereHas('goalPlan', function ($q) use ($plan) {
                        $q->where('plan_id', $plan);
                    })->where('check', '!=', 0)->where(function ($query) use ($firstWeek) {
                        foreach ($firstWeek as $d) {
                            $query->orWhereDate('updated_at', '=', Carbon::parse($d->date));
                        }
                    })
                    ->count();

                $totalRateWeekOne = intval(($countWeekOne / 21) * 100);
                $countWeekTwo = Target::where('user_id', auth()->id())
                    ->whereHas('goalPlan', function ($q) use ($plan) {
                        $q->where('plan_id', $plan);
                    })->where('check', '!=', 0)->where(function ($query) use ($secondWeek) {
                        foreach ($secondWeek as $d) {
                            $query->WhereDate('updated_at', '=', Carbon::parse($d->date));
                        }
                    })
                    ->count();

                $totalRateWeekTwo = intval(($countWeekTwo / 21) * 100);
                $countDay = Target::where('user_id', auth()->id())
                    ->whereHas('goalPlan', function ($q) use ($plan) {
                        $q->where('plan_id', $plan);
                    })->where('check', '!=', 0)->whereDate('updated_at', $today)
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
                            $countDayTotal = Target::where('user_id', auth()->id())
                                ->whereHas('goalPlan', function ($q) use ($plan) {
                                    $q->where('plan_id', $plan);
                                })->where('check', '!=', 0)->whereDate('updated_at', $d->date) > count();
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
                $exe =  Plan::where('id', $plan)->with(['exercise' => function ($q) use ($day, $week) {
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
                $type = "success";
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = "This plan is not available to you or is not intended for exercise.😊";
        }

        return response()->json(['data' => $exe, 'type' => $type,  'message' => $message]);
    }

    public function getSleep()
    {
        $today = Carbon::today();
        $message = '';
        $sleep = '';
        $type = 'error';
        $check = Target::where('user_id', auth()->id())->count();
        if ($check) {
            $checkAtice = Target::where('user_id', auth()->id())->where('active', 1)->count();
            if ($checkAtice) {
                $sleep =  Plan::where('id', 14)->with(['targets' => function ($q) use ($today) {
                    $q->where('user_id', auth()->id())->where('sleep', '!=', null)->whereDate('targets.created_at', $today);
                }])->first();
                $type = 'success';
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = "If you want to see more details please register with this goal and don't forget to check your email address 😉😉";
        }


        return response()->json(['data' => $sleep, 'message' => $message, 'type' => $type]);
    }

    public function getWater()
    {
        $today = Carbon::today();
        $message = '';
        $water = '';
        $type = 'error';
        $check = Target::where('user_id', auth()->id())->count();
        if ($check) {
            $checkAtice = Target::where('user_id', auth()->id())->where('active', 1)->count();
            if ($checkAtice) {
                $water =  Plan::where('id', 15)->with(['targets' => function ($q) use ($today) {
                    $q->where('user_id', auth()->id())->where('water', '!=', null)->whereDate('targets.created_at', $today);
                }])->first();
                $type = 'success';
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = "If you want to see more details please register with this goal and don't forget to check your email address 😉😉";
        }


        return response()->json(['data' => $water, 'message' => $message, 'type' => $type]);
    }

    public function getExerciseForPlan($plan)
    {
        $exe =  Plan::where('id', $plan)->with(['exercise', 'exercise.media'])->get();
        return response()->json(['data' => $exe]);
    }


    public function meal($day, $week, GetMealRequest $request)
    {
        $exe = [];
        $today = Carbon::now();
        $message = '';
        $plan_id = 0;
        $type = 'error';
        $check = Target::where('user_id', auth()->id())->count();
        if ($check) {
            $target = Target::where('user_id', auth()->id())->where('active', 1)->with(['goalPlan', 'users.date'])->first();
            if ($target) {
                $plan_id = Plan::whereHas('goals', function ($q) use ($target) {
                    $q->where('goal_id', $target->goalPlan->goal_id);
                })
                    ->where('type', 'food')
                    ->first();
                $allMeals =  Plan::where('id', $plan_id->id)->with(['meal' => function ($q) use ($day, $week) {
                    $q->where('day', $day)->where('week', $week);
                }, 'meal.media'])->get();
                if ($request->breakfast) {
                    $exe =  Plan::where('id', $plan_id->id)->with(['targets' => function ($q) use ($today) {
                        $q->where('user_id', auth()->id())->where('check', '!=', 0)->whereDate('targets.created_at', $today);
                    }, 'targets.users', 'meal' => function ($q) use ($day, $week) {
                        $q->where('day', $day)->where('week', $week)->where('breakfast', 1);
                    }, 'meal.media'])->get();
                } else if ($request->lunch) {
                    $exe =  Plan::where('id', $plan_id->id)->with(['targets' => function ($q) use ($today) {
                        $q->where('user_id', auth()->id())->where('check', '!=', 0)->whereDate('targets.created_at', $today);
                    }, 'targets.users', 'meal' => function ($q) use ($day, $week) {
                        $q->where('day', $day)->where('week', $week)->where('lunch', 1);
                    }, 'meal.media'])->get();
                } else {
                    $exe =  Plan::where('id', $plan_id->id)->with(['targets' => function ($q) use ($today) {
                        $q->where('user_id', auth()->id())->where('check', '!=', 0)->whereDate('targets.created_at', $today);
                    }, 'targets.users', 'meal' => function ($q) use ($day, $week) {
                        $q->where('day', $day)->where('week', $week)->where('dinner', 1);
                    }, 'meal.media'])->get();
                }

                //get meal bars
                $xx = [];
                $yy = [];
                $FoodForDay = Target::selectRaw('DATE(created_at) as x, SUM(calories) as y')
                    ->whereHas('goalPlan.plan', function ($q) {
                        $q->where('type', 'food');
                    })
                    ->where('user_id', auth()->id())
                    ->groupBy('x')
                    ->get();

                foreach ($FoodForDay as $data) {
                    array_push($xx, $data->x ? $data->x : 0);
                    array_push($yy, $data->y ? $data->y  : 0);
                }

                $exe[0]->allMeals = $allMeals;
                $type = 'success';
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = "If you want to see more details please register with this goal and don't forget to check your email address 😉😉";
        }

        return response()->json([
            'data' => $exe,
            'date' => $target->users->date,
            'id' => $plan_id ? $plan_id->id : 0,
            'type' => $type,
            'x' => $xx,
            'y' => $yy,
            'message' => $message
        ]);
    }


    public function getUserPlans()
    {
        $message = '';
        $newIndex = [];
        $type = 'error';
        $CountGetdate = Target::where('user_id', auth()->id())->count();
        if ($CountGetdate) {
            $CountGetdateWithActive = Target::where('user_id', auth()->id())->where('active', 1)->count();
            if ($CountGetdateWithActive) {
                $plans = Plan::query()
                    ->whereHas('targets', function ($q) {
                        $q->where('user_id', auth()->id())->where('check', '!=', 0);
                    })
                    ->where('type', '!=', 'food')
                    ->where('type', '!=', 'sleep')
                    ->where('type', '!=', 'water')

                    ->with(['targets' => function ($query) {
                        $query->where('user_id', auth()->id())->where('check', '!=', 0);
                    }, 'media', 'targets.goalPlan'])
                    ->get();
                if ($plans) {
                    $newIndex = $plans->map(function ($i) {
                        $count = Target::where('user_id', auth()->id())
                            ->whereHas('goalPlan', function ($q) use ($i) {
                                $q->where('plan_id', $i->id);
                            })->where('check', '!=', 0)
                            ->count();
                        $totalRate = intval(($count / 42) * 100);
                        $i->totalRate = $totalRate;
                        return $i;
                    });
                } else {
                    $message = "Don't you want to burn the fat? 💪💪What your waiting for start your first plan with us now 🔥🔥";
                }

                $type = 'success';
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = "If you want to see more details please register with this goal and don't forget to check your email address 😉😉";
        }

        return response()->json(['data' => $newIndex, 'type' => $type, 'message' => $message]);
    }


    public function showPlan(Request $request, $id)
    {
        $arr = [];
        $arrDay = [];
        $date = [];
        $gender = Auth::user()->gender;
        $ar = $gender === 'male' ? array(6, 7, 8, 9, 10) : array(1, 2, 3, 4, 5);
        $type = 'error';
        $message = '';
        $today = Carbon::today();
        $show = [];
        $CountGetdate = Target::where('user_id', auth()->id())->whereHas('goalPlan', function ($q) use ($id) {
            $q->where('plan_id', $id);
        })->count();
        if ($CountGetdate) {
            $CountGetdateActive = Target::where('user_id', auth()->id())->where('active', 1)->count();
            if ($CountGetdateActive) {
                $Getdate = Target::where('user_id', auth()->id())->with('users.date')->first();
                $date = $Getdate->users->date;
                $firstWeek = [$date[0], $date[1], $date[2], $date[3], $date[4], $date[5], $date[6]];
                $secondWeek = [$date[7], $date[8], $date[9], $date[10], $date[11], $date[12], $date[13]];
                $count = Target::where('user_id', auth()->id())
                    ->whereHas('goalPlan', function ($q) use ($id) {
                        $q->where('plan_id', $id);
                    })->whereHas('goalPlan.plan.exercise', function ($q) {
                        $q->where('type', Auth::user()->gender);
                    })
                    ->whereIn('check', $ar)->count();
                $totalRate = intval(($count / 21) * 100);
                $countWeekOne = Target::where('user_id', auth()->id())
                    ->whereHas('goalPlan', function ($q) use ($id) {
                        $q->where('plan_id', $id);
                    })->where('check', '!=', 0)->where(function ($query) use ($firstWeek) {
                        foreach ($firstWeek as $d) {
                            $query->orWhereDate('updated_at', '=', Carbon::parse($d->date));
                        }
                    })
                    ->count();

                $totalRateWeekOne = intval(($countWeekOne / 21) * 100);
                $countWeekTwo = Target::where('user_id', auth()->id())
                    ->whereHas('goalPlan', function ($q) use ($id) {
                        $q->where('plan_id', $id);
                    })->where('check', '!=', 0)->where(function ($query) use ($secondWeek) {
                        foreach ($secondWeek as $d) {
                            $query->WhereDate('updated_at', '=', Carbon::parse($d->date));
                        }
                    })
                    ->count();

                $totalRateWeekTwo = intval(($countWeekTwo / 21) * 100);
                $countDay = Target::where('user_id', auth()->id())
                    ->whereHas('goalPlan', function ($q) use ($id) {
                        $q->where('plan_id', $id);
                    })->where('check', '!=', 0)->WhereDate('updated_at', $today)->count();

                $totalRateDay = intval(($countDay / 3) * 100);
                if ($request->type) {
                    if ($request->type === 'weekly') {
                        if ($request->number_week == 1) {
                            $arr = $firstWeek;
                        } else if ($request->number_week == 2) {
                            $arr = $secondWeek;
                        }
                        foreach ($arr as $d) {
                            $countDayTotal = Target::where('user_id', auth()->id())
                                ->whereHas('goalPlan', function ($q) use ($id) {
                                    $q->where('plan_id', $id);
                                })->where('check', '!=', 0)->whereDate('updated_at', $d->date)->count();
                            $totalRateDayTotal = intval(($countDayTotal / 3) * 100);
                            array_push($arrDay, ['x' => $d->date, 'y' => $totalRateDayTotal]);
                        }
                    } else {
                        array_push($arrDay, ["x" => 'first week', 'y' => $totalRateWeekOne]);
                        array_push($arrDay, ['x' => "scound week", 'y' => $totalRateWeekTwo]);
                    }
                }
                $show = Plan::where('id', $id)
                    ->with(['targets' => function ($q) use ($today) {
                        $q->where('user_id', auth()->id())->whereDate('targets.created_at', $today)->where('check', '!=', 0);
                    }, 'targets.users', 'targets.users.date', 'media'])
                    ->first();
                $show->date =   $date;
                $show->totalRate =   $totalRate;
                $show->totalRateDay =   $totalRateDay;
                $show->totalRateWeekOne =   $totalRateWeekOne;
                $show->totalRateWeekTwo =   $totalRateWeekTwo;
                $show->arrDay = $arrDay;
                $type = 'success';
            } else {
                $message = 'please wait to processing the goal';
            }
        } else {
            $message = "This plan is not available to you or is not intended for goal.😊";
        }
        return response()->json(['data' => $show, 'type' => $type, 'message' => $message]);
    }

    // public function showPlan(Plan $Plan)
    // {
    //     $i = $Plan->load(['plan', 'plan.media',  'level', 'exercise', 'exercise.media']);
    //     return response()->json($i);
    // }
}
