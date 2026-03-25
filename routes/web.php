<?php

use Illuminate\Support\Facades\File;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Jenssegers\Agent\Agent;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Profile\RepresentativeController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\ProfileMsgController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserAccessToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\ValidateUserToken;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CookieConsentController;

Route::get('/activate-account', [\App\Http\Controllers\ActivateController::class, 'activate']);

Route::get('/private-file/{type}/{userId}/{filename}', function ($type, $userId, $filename) {
    $allowedTypes = ['users', 'members'];
    if (!in_array($type, $allowedTypes)) {
        abort(404);
    }

    $mainStoragePath = '/home/pachim/ino-official.org/storage/app/private/public';

    $path = $mainStoragePath . "/{$type}/{$userId}/{$filename}";

    if (!file_exists($path)) {
        abort(404);
    }

    $mimeType = mime_content_type($path);

    return response()->file($path, ['Content-Type' => $mimeType]);
})->name('private.file');

Route::get('/private-asset-allocated/{type}/{userId}/{filename}', function ($type, $userId, $filename) {
    $allowedTypes = ['users', 'members'];
    if (!in_array($type, $allowedTypes)) {
        abort(404);
    }

    $relativePath = "{$type}/{$userId}/{$filename}";

    if (!\Illuminate\Support\Facades\Storage::disk('private')->exists($relativePath)) {
        abort(404);
    }

    $fileContent = \Illuminate\Support\Facades\Storage::disk('private')->get($relativePath);
    $mimeType = \Illuminate\Support\Facades\Storage::disk('private')->mimeType($relativePath);

    return response($fileContent, 200)
        ->header('Content-Type', $mimeType);
})->name('private.allocated');

Route::get('clientarea/login',function(){
    return redirect('/login');
});

//Route::get('loginUsingId/{id}',function($id){
//    Auth::loginUsingId($id);
//});

Route::get('/login', function () {
    if(auth()->check()){
        DB::table('user_access_tokens')->where('user_id', auth()->user()->id)->delete();

        auth()->logout();
    }

    return view('auth.login');
})->name('login');

Route::get('/captcha-image', function () {
    $builder = new CaptchaBuilder;
    $builder->build();

    session(['captcha_phrase' => $builder->getPhrase()]);

    return response($builder->output())
        ->header('Content-Type', 'image/jpeg');
});

Route::post('/do/login', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string',
        'captcha' => ['required', function ($attribute, $value, $fail) {
            if (strtolower($value) !== strtolower(session('captcha_phrase'))) {
                $fail('Invalid captcha.');
            }
        }],
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    $email = $request->input('email');
    $password = $request->input('password');
    $remember = $request->has('remember');

    $user = User::where('email', $email)->first();

    if (!$user || !password_verify($password, $user->password)) {
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    if ($user->is_active == 0) {
        if ($user->email_verified_at !== null) {
            $user->is_active = 1;
        } else {
            Mail::to($user->email)->send(new \App\Mail\RegistrationEmail($user));

            return back()->withSuccess([
                'email' => 'Your email is not activated yet. Please check your inbox and spam folder to activate your account.',
            ])->withInput();
        }
    }

    auth()->login($user, $remember);

    $user->last_login = now();
    $user->save();

    // Notify front-end (optional)
    try {
        $userId = $user->id;
        $url = rtrim(env('URL_FRONT'), '/') . "/api/log-user-in/{$userId}";

        $client = new \GuzzleHttp\Client();
        $response = $client->get($url);

        if ($response->getStatusCode() !== 200) {
            \Log::warning("Failed to notify front-end login for user ID {$userId}");
        }
    } catch (\Exception $e) {
        \Log::error("Error calling front-end login API: " . $e->getMessage());
    }

    // Generate new token
    $token = bin2hex(random_bytes(32));
    $expiresAt = now()->addMinutes(60);
    $unixExpiry = $expiresAt->timestamp;

    // Collect user metadata
    $ipAddress = $request->ip();
    $userAgent = $request->userAgent();
    $agent = new Agent();
    $deviceName = $agent->platform() . ' - ' . $agent->browser();

    // Remove old tokens
    UserAccessToken::where('user_id', $user->id)->delete();

    DB::table('user_access_tokens')->insert([
        'user_id' => $user->id,
        'token' => $token,
        'expires_at' => $expiresAt,
        'unix_expiry_timestamp' => $unixExpiry,
        'ip_address' => $ipAddress,
        'user_agent' => $userAgent,
        'device_name' => $deviceName,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    try {
        $response = Http::post(env('URL_FRONT') . '/api/user-logged-in', [
            'user_id' => $user->id,
            'token' => $token
        ]);


        if ($response->failed()) {
            \Log::warning("Failed to notify root project of login for user {$user->id}");
        }

        $url = env('URL_FRONT') . '/api/log-user-in/' . $user->id;
        $response2 = $client->get($url);

        if ($response2->getStatusCode() !== 200) {
            \Log::warning("Failed to notify front-end login for user ID {$user->id}. Status: " . $response2->getStatusCode() . " Response: " . $response2->getBody());
        }
    } catch (\Exception $e) {
        \Log::error("Exception while notifying root project login API: " . $e->getMessage());
    }

    return redirect(env('URL_PANEL') . "?auth_token=" . urlencode($token));
})->name('login.do');

Route::get('/verify', function (Request $request) {
    $email = $request->query('email');

    $user = User::where('email', $email)->first();

    if (!$user) {
        return redirect('/')->withErrors('Invalid verification link.');
    }

    $user->email_verified_at = now();
    $user->is_active = 1;
    $user->save();

    Session::put('emailVerified', $user->email);
    Session::put('success', 'Your email has been activated successfully! Please login to your account.');

    return redirect('/');
})->name('cla.verify');

Route::middleware([ValidateUserToken::class])->group(function () {
    Route::get('/cookie-consent', [CookieConsentController::class, 'store'])->name('cookie.consent');

    Route::get('/', function () {
        $userCoursesCount = DB::table('course_registrations')->where('user_id',auth()->user()->id)->count();
        $coursesNum = DB::table('courses')->where('is_active',1)->count();
        $episodesNum = DB::table('episodes')->where('show_status',1)->count();

        return view('index', compact('userCoursesCount','coursesNum','episodesNum'));
    })->name('dashboard');

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/edit/update', [ProfileController::class, 'update'])->name('update');
        Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
        Route::put('/apply/settings', [ProfileController::class, 'applySettings'])->name('apply');
        Route::get('/security', [ProfileController::class, 'security'])->name('security');
        Route::put('/adjust/security', [ProfileController::class, 'adjustSecurity'])->name('adjust');
        Route::get('/notifications', [ProfileController::class, 'notifications'])->name('notifications');
        Route::put('/set/notifications', [ProfileController::class, 'setNotifications'])->name('set');
        Route::get('/password', [ProfileController::class, 'password'])->name('password');
        Route::put('/change/password', [ProfileController::class, 'changePassword'])->name('change');
        Route::post('/logout', [ProfileController::class, 'logout'])->name('logout');

        Route::group(['prefix' => 'messages', 'as' => 'msg.'], function () {
            Route::get('/inbox', [ProfileMsgController::class, 'inbox'])->name('inbox');
            Route::get('/compose', [ProfileMsgController::class, 'compose'])->name('send');
            Route::post('/compose/submit', [ProfileMsgController::class, 'submitCompose'])->name('submit.compose');
            Route::get('/sent', [ProfileMsgController::class, 'sent'])->name('sent');
            Route::get('/show/{id}', [ProfileMsgController::class, 'show'])->name('show');
            Route::get('/submit/reply/{id}', [ProfileMsgController::class, 'reply'])->name('reply');
        });
    });

    Route::group(['prefix' => 'courses', 'as' => 'courses.'], function () {
        Route::get('/', [CourseController::class, 'index'])->name('index');
        Route::get('/course-player/{slug}', [CourseController::class, 'show'])->name('show');
        Route::get('/course-player/{slug}/{epi?}',[CourseController::class,'showCourseNextEpisode'])->name('show.rest');
        Route::get('/registered_courses', [CourseController::class, 'enrolled'])->name('enrolled');
        Route::get('/progress', [CourseController::class, 'progress'])->name('progress');
    });

    Route::group(['prefix' => 'rep', 'as' => 'rep.'], function () {
        Route::get('/past', [RepresentativeController::class, 'past'])->name('past');

        Route::get('/register', [RepresentativeController::class, 'register'])->name('reg');
        Route::post('/register/submit', [RepresentativeController::class, 'doRegister'])->name('reg.submit');

        Route::get('/registered_users', [RepresentativeController::class, 'allRegisteredUsers'])->name('reg.all');

        Route::get('/bulk/excel', [RepresentativeController::class, 'bulkExcel'])->name('bulk-excel');
        Route::post('/bulk/excel', [RepresentativeController::class, 'bulkExcelUpload'])->name('bulk-excel.upload');

        Route::get('/bulk/bulk/text', [RepresentativeController::class, 'bulkText'])->name('bulk-text');
        Route::post('/bulk/bulk/text/submit', [RepresentativeController::class, 'bulkTextSubmit'])->name('bulk-text.submit');

        Route::get('/bulk/text', [RepresentativeController::class, 'bulkReferral'])->name('bulk-referral');
        Route::post('/bulk/text/submit', [RepresentativeController::class, 'bulkReferralSubmit'])->name('bulk-referral.submit');

        Route::get('/progress', [RepresentativeController::class, 'progress'])->name('progress');
        Route::get('/stats', [RepresentativeController::class, 'stats'])->name('stats');
    });

    Route::get('/logout', function () {
        \DB::table('user_access_tokens')->where('user_id',auth()->user()->id)->delete();

        Auth::logout(auth()->user());

        Http::post(env('URL_FRONT').'/api/logout', [
            'user_id' => auth()->id(),
        ]);

        return redirect('/clientarea/login')->with('error','Your session has expired. Please login again.');
    })->name('logout');

    Route::get('user-image/{folder}/{userId}/{filename}', function ($folder, $userId, $filename) {
        // Security: only allow 'users' or 'members' folders
        if (!in_array($folder, ['users', 'members'])) {
            abort(404);
        }

        $path = storage_path("app/private/public/{$folder}/{$userId}/{$filename}");

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        return response($file, 200)->header('Content-Type', $type);
    });

    Route::get('/courses/all',function(){

    });
});
