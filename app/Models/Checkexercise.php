<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkexercise extends Model
{
    use HasFactory;
    protected $fillable =  [
        'check_id'
    ];

    public function target()
    {
        return $this->belongsTo(Target::class);
    }
}
