<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $fillable =  [
        'name',
        'type',
        'lastMessage',
        "start_time",
        "end_time",
        "day"
    ];
    public function user()
    {
        return $this->belongsToMany(
            User::class,
            'groups',
            'chat_id',
            'user_id'
        );
    }
}
