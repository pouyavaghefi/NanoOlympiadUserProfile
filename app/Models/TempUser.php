<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempUser extends Model
{
    protected $table = 'temp_users';
    protected $guarded = [];

    protected $casts = [
        'confirmed_by_admin' => 'boolean',
        'confirmed_by_user' => 'boolean',
        'submitted_at' => 'datetime',
        'confirmed_by_admin_at' => 'datetime',
        'confirmed_by_user_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
