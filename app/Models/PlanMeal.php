<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanMeal extends Model
{
    use HasFactory;
    protected $table = 'plan_meals';

    protected $fillable = [
        'plan_id',
        'meal_id',
        'day',
        'week',
        'breakfast',
        'lunch',
        'dinner',
        'snacks'
    ];
}
