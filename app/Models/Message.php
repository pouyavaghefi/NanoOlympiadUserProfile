<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipients()
    {
        return $this->belongsToMany(User::class, 'message_recipient', 'message_id', 'user_id')
            ->withTimestamps();
    }

    public function replies()
    {
        return $this->hasMany(MessageReply::class);
    }

    // In Message model
    public function recipientForUser($userId)
    {
        return $this->hasOne(MessageRecipient::class)->where('user_id', $userId);
    }


}
