<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CookieConsent;
class CookieConsentController extends Controller
{
    public function store(Request $request)
    {
        $consent = $request->input('consent'); // 'accepted' or 'declined'

        $data = [
            'consent' => $consent,
        ];

        if (auth()->check()) {
            $data['user_id'] = auth()->id();
        } else {
            $data['session_token'] = $request->cookie('session_token');
        }

        CookieConsent::updateOrCreate(
            ['user_id' => $data['user_id'] ?? null, 'session_token' => $data['session_token'] ?? null],
            $data
        );

        return response()->json(['status' => 'ok']);
    }
}
