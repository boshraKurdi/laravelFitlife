<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'exercise_id',
        'day',
        'week'
    ];
    public function exercises()
    {
        return $this->belongsTo(Exercise::class, 'exercise_id');
    }
}
