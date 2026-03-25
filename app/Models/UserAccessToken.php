<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccessToken extends Model
{
    protected $guarded = [];
    protected $table = 'user_access_tokens';
}
