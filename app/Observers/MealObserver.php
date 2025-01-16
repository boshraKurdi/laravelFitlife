<?php

namespace App\Observers;

use App\Models\Meal;

class MealObserver
{
    /**
     * Handle the Meal "created" event.
     */
    public function created(Meal $meal): void
    {
        //
    }

    /**
     * Handle the Meal "updated" event.
     */
    public function updated(Meal $meal): void
    {
        //
    }

    /**
     * Handle the Meal "deleted" event.
     */
    public function deleted(Meal $meal): void
    {
        //
    }

    /**
     * Handle the Meal "restored" event.
     */
    public function restored(Meal $meal): void
    {
        //
    }

    /**
     * Handle the Meal "force deleted" event.
     */
    public function forceDeleted(Meal $meal): void
    {
        //
    }
}
