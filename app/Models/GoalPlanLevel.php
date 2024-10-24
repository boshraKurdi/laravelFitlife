<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GoalPlanLevel extends Model
{
    use HasFactory;
    protected $table = 'goal_plan_levels';

    protected $fillable = [
        'goal_id',
        'plan_level_id',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'targets',
        );
    }
    public function goals()
    {
        return $this->belongsTo(Goal::class, 'goal_id');
    }
    public function planLevels()
    {
        return $this->belongsTo(PlanLevel::class, 'plan_level_id');
    }
}
