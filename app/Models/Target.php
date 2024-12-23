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
        'goal_plan_level_id',
        'calories',
        'active',
        'rate',
        'check_1',
        'check_2',
        'check_3',
    ];
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function goalPlanLevel()
    {
        return $this->belongsTo(GoalPlanLevel::class, 'goal_plan_level_id');
    }
    public function check()
    {
        return $this->hasMany(Checkexercise::class);
    }
}
