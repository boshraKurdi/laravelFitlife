<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GoalPlan extends Model
{
    use HasFactory;
    protected $table = 'goal_plans';

    protected $fillable = [
        'goal_id',
        'plan_id',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'targets',
            'goal_plan_id',
            'user_id'
        )->withTimestamps()->withPivot(['calories', 'id']);
    }
    public function targets()
    {
        return $this->hasMany(
            Target::class,
        );
    }
    public function goals()
    {
        return $this->belongsTo(Goal::class, 'goal_id');
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
