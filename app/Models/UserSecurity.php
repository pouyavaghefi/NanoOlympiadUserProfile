<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSecurity extends Model
{
    protected $table = 'user_security_adjustments';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
