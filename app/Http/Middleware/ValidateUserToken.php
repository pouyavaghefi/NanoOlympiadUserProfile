<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAccessToken;
use Carbon\Carbon;
use DB;
use Redirect;
use Symfony\Component\HttpFoundation\Response;

class ValidateUserToken
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('logout')) {
            return $next($request);
        }

        if (auth()->check() == false) {
            $token = $request->query('auth_token');

            $authUrl = env('URL_AUTH','https://profile.nanolympiad.org/login'); // login!

            if (!$authUrl) {
                $authUrl = "https://profile.nanolympiad.org/logout";
            }

            if (!$token) {
                return redirect($authUrl);
            }

            $userToken = UserAccessToken::where('token', $token)->first();
            if (!$userToken) {
                return redirect()->away($authUrl);
            }

            if (Carbon::parse($userToken->expires_at)->isPast()) {
                return redirect($authUrl);
            }

            $user = DB::table('users')->where('id', $userToken->user_id)->first();
            if (!$user) {
                return redirect($authUrl);
            }

            Auth::loginUsingId($user->id);
            return $next($request);
        }else{
            $token = $request->query('auth_token');

            $authUrl = env('URL_AUTH','https://profile.nanolympiad.org/login'); // login!

            if (!$authUrl) {
                $authUrl = "https://profile.nanolympiad.org/logout";
            }

            $userToken = UserAccessToken::where('user_id', auth()->user()->id)->first();

            if (!$userToken) {
                return redirect()->away($authUrl);
            }

            if (Carbon::parse($userToken->expires_at)->isPast()) {
                return redirect($authUrl);
            }

            if (!$userToken || $userToken->token !== $token) {
                $currentUrl = $request->url();
                $queryParams = $request->query();

                $queryParams['auth_token'] = $userToken ? $userToken->token : null;

                if (!$queryParams['auth_token']) {
                    return redirect()->away($authUrl);
                }

                $redirectUrl = $currentUrl . '?' . http_build_query($queryParams);

                return redirect($redirectUrl);
            }
        }

        return $next($request);
    }
}