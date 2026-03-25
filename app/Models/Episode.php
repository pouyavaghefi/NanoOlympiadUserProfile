<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Episode extends Model
{
    protected $table = 'episodes';
    protected $guarded = [];

    public function likes($id)
    {
        return DB::table('episode_reactions')->where('reaction','like')->where('episode_id',$id)->count();
    }

    public function dislikes($id)
    {
        return DB::table('episode_reactions')->where('reaction','dislike')->where('episode_id',$id)->count();
    }
}
