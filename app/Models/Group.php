<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable =  [
        'chat_id',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
    public function messages()
    {
        return $this->hasMany(
            Message::class,
        );
    }
}
