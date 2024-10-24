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
        'duration',
        'rate'
    ];
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
