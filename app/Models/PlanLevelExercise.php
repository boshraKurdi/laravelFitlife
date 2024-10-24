<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanLevelExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_level_id',
        'exercise_id',
        'day',
        'week'
    ];
}
