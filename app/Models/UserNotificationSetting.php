<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotificationSetting extends Model
{
    protected $table = 'user_notification_settings';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
