<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanLevelMeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_level_id',
        'meal_id',
        'day',
        'week',
        'breakfast',
        'lunch',
        'dinner',
        'snacks'
    ];
}
