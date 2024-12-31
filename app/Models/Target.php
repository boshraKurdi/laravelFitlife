<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;
    protected $table = 'targets';
    protected $fillable = [
        'user_id',
        'goal_plan_id',
        'calories',
        'active',
        'check',
        'water',
        'sleep'
    ];
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function goalPlan()
    {
        return $this->belongsTo(GoalPlan::class, 'goal_plan_id');
    }
}
