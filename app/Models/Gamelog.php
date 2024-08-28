<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gamelog extends Model
{
    protected $fillable = [
        'user_id',
        'winnings',
        'game',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
