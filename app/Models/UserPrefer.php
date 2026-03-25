<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPrefer extends Model
{
    protected $table = 'user_preferences';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
