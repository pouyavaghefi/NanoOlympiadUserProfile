<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ActivateController extends Controller
{
    public function activate(Request $request)
    {
        $token = $request->query('token');

        $user = DB::table('temp_users')->where('token', $token)->first();

        if (!$user) {
            abort(404, 'Invalid token.');
        }

        DB::table('temp_users')->where('id', $user->id)->update([
            'user_status' => 1,
            'confirmed_by_user' => 1,
            'updated_at' => now(),
        ]);

        return redirect('/login')->with('success','Your account has been added now, please sign in with your credits.');
    }
}
