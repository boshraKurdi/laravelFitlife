<?php

namespace App\Observers;

use App\Models\GoalPlan;
use App\Models\Update;

class GoalPlanObserver
{
    /**
     * Handle the GoalPlan "created" event.
     */
    public function created(GoalPlan $goalPlan): void
    {
        //
    }
    public function update(): void
    {
        Update::create();
    }

    /**
     * Handle the GoalPlan "updated" event.
     */
    public function updated(GoalPlan $goalPlan): void {}

    /**
     * Handle the GoalPlan "deleted" event.
     */
    public function deleted(GoalPlan $goalPlan): void
    {
        //
    }

    /**
     * Handle the GoalPlan "restored" event.
     */
    public function restored(GoalPlan $goalPlan): void
    {
        //
    }

    /**
     * Handle the GoalPlan "force deleted" event.
     */
    public function forceDeleted(GoalPlan $goalPlan): void
    {
        //
    }
}
