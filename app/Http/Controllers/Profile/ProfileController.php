<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSecurity;
use DB;
use Hash;
use Auth;
use Str;
use Storage;
class ProfileController extends ProfileMainController
{
    public function index()
    {
        return view('profile.index');
    }

    public function edit()
    {
        $user = auth()->user();

        $directory = "private/public/users/{$user->id}";

        $files = [];

        if (Storage::disk('main_root')->exists($directory)) {
            $files = collect(Storage::disk('main_root')->files($directory))
                ->map(fn($file) => basename($file))
                ->all();
        }

        $detail = $user->member;

        return view('profile.edit', compact('user', 'detail', 'files'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        // Validate the request data
        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Extended details validation
            'surname' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'passport_number' => 'nullable|string|max:255',
            'passport_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'nullable|in:male,female',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string|max:20',
            'referer_code' => 'nullable|string|max:255',
            'agent_name' => 'nullable|string|max:255',
            'agent_code' => 'nullable|string|max:255',
        ]);

        // Update or create member details
        $memberData = [
            'surname' => $validated['surname'],
            'father_name' => $validated['father_name'],
            'birthday' => $validated['birthday'],
            'passport_number' => $validated['passport_number'],
            'gender' => $validated['gender'],
            'phone' => $validated['phone'],
            'country' => $validated['country'],
            'city' => $validated['city'],
            'address' => $validated['address'],
            'postal_code' => $validated['postal_code'],
            'referer_code' => $validated['referer_code'],
            'agent_name' => $validated['agent_name'],
            'agent_code' => $validated['agent_code'],
        ];

        try {
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store("users/{$user->id}", 'private');
                $validated['avatar'] = $path;

                // Sync avatar with main project
                $this->syncAvatarWithMainProject($user, $request->file('avatar'));
            }

            // Update user basic info
            $user->update([
                'fname' => $validated['fname'],
                'lname' => $validated['lname'],
            ]);

            // Handle passport photo upload
            if ($request->hasFile('passport_photo')) {
                $path = $request->file('passport_photo')->store("members/{$user->id}", 'private');
                $validated['passport_photo'] = $path;

                $this->syncPassportWithMainProject($user, $request->file('passport_photo'));
            }

            $user->member()->updateOrCreate(['user_id' => $user->id], $memberData);

            return redirect()->route('profile.edit', ['auth_token' => $request->query('auth_token')])
                ->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Profile update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Profile update failed: '.$e->getMessage());
        }
    }

    protected function syncProfileWithMainProject($user)
    {
        try {
            $certPath = storage_path('app/certs/cacert.pem');
            $client = new \GuzzleHttp\Client([
                'verify' => file_exists($certPath) ? $certPath : true,
                'timeout' => 30,
            ]);

            $response = $client->post(config('services.main_project.base_url') . '/api/sync-profile', [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('services.main_project.api_key'),
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'user_id'      => $user->id,
                    'email'        => $user->email,
                    'fname'        => $user->fname,
                    'lname'        => $user->lname,
                    'uname'        => $user->uname,
                    'avatar'       => $user->avatar,
                    'member_data'  => $user->member->toArray(),
                ],
            ]);

            $responseData = json_decode($response->getBody(), true);

            if (!isset($responseData['success'])) {
                throw new \Exception('Invalid API response format');
            }

            return $responseData;

        } catch (\Exception $e) {
            \Log::error('Profile sync failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString()
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    protected function syncAvatarWithMainProject($user, $avatarFile)
    {
        try {
            $client = new \GuzzleHttp\Client([
                'verify' => file_exists(storage_path('app/certs/cacert.pem'))
                    ? storage_path('app/certs/cacert.pem')
                    : true,
                'timeout' => 30,
            ]);

            $response = $client->post(config('services.main_project.base_url') . '/api/sync-avatar', [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('services.main_project.api_key'),
                    'Accept' => 'application/json',
                ],
                'multipart' => [
                    [
                        'name' => 'user_id',
                        'contents' => $user->id
                    ],
                    [
                        'name' => 'avatar',
                        'contents' => fopen($avatarFile->getRealPath(), 'r'),
                        'filename' => $avatarFile->getClientOriginalName(),
                        'headers' => [
                            'Content-Type' => $avatarFile->getMimeType()
                        ]
                    ]
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);

            if (!isset($responseData['success'])) {
                throw new \Exception('Invalid API response format');
            }

            return $responseData;

        } catch (\Exception $e) {
            \Log::error('Avatar sync failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    protected function syncPassportWithMainProject($user, $passportFile)
    {
        try {
            $client = new \GuzzleHttp\Client([
                'verify' => file_exists(storage_path('app/certs/cacert.pem'))
                    ? storage_path('app/certs/cacert.pem')
                    : true,
                'timeout' => 30,
            ]);

            $response = $client->post(config('services.main_project.base_url') . '/api/sync-passport', [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('services.main_project.api_key'),
                    'Accept' => 'application/json',
                ],
                'multipart' => [
                    [
                        'name' => 'user_id',
                        'contents' => $user->id
                    ],
                    [
                        'name' => 'member_id',
                        'contents' => $user->member->id
                    ],
                    [
                        'name' => 'passport_photo',
                        'contents' => fopen($passportFile->getRealPath(), 'r'),
                        'filename' => $passportFile->getClientOriginalName(),
                        'headers' => [
                            'Content-Type' => $passportFile->getMimeType()
                        ]
                    ]
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);

            if (!isset($responseData['success'])) {
                throw new \Exception('Invalid API response format');
            }

            return $responseData;

        } catch (\Exception $e) {
            \Log::error('Passport sync failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function settings(Request $request)
    {
        $user = Auth::user();

        $languages = DB::table('languages')->where('is_active', 1)->get();
        $settings = $user->settings()->first();

        return view('profile.account-settings', compact('languages', 'settings'));
    }


    public function applySettings(Request $request)
    {
        $request->validate([
            'timezone' => 'required|timezone',
            'date_format' => 'required|in:Y-m-d,d/m/Y,m/d/Y',
        ]);

        $user = Auth::user();

        $settings = $user->settings()->firstOrNew([]);

        $settings->timezone = $request->timezone;
        $settings->date_format = $request->date_format;
        $settings->save();

        return redirect()->route('profile.settings', ['auth_token' => $request->query('auth_token')])
            ->with('success', 'Profile updated successfully!');
    }

    public function security()
    {
        $user = Auth::user();

        $security = $user->security()->firstOrNew([]);

        return view('profile.security', compact( 'security'));
    }

    public function adjustSecurity(Request $request)
    {
        $request->validate([
            'backup_email' => 'nullable|email',
            'trusted_ip' => 'nullable|ip',
        ]);

        $user = Auth::user();

        $security = $user->security()->firstOrNew([]);

        $security->two_factor_enabled = $request->has('two_factor_enabled');
        $security->email_notifications = $request->has('email_notifications');
        $security->login_alerts = $request->has('login_alerts');
        $security->allow_password_reset = $request->has('allow_password_reset');
        $security->suspicious_login_protection = $request->has('suspicious_login_protection');
        $security->backup_email = $request->backup_email;
        $security->trusted_ip = $request->trusted_ip;

        $security->save();

        return redirect()->route('profile.security', ['auth_token' => $request->query('auth_token')])
            ->with('success', 'Security settings updated successfully!');
    }

    public function notifications()
    {
        $user = Auth::user();

        $notifications = $user->notificationsSettings()->firstOrNew([]);

        return view('profile.notification-settings', compact( 'notifications'));
    }

    public function setNotifications(Request $request)
    {
        $user = Auth::user();

        $notifications = $user->notificationsSettings()->firstOrNew([]);

        $notifications->system_alerts = $request->has('system_alerts');
        $notifications->news_updates = $request->has('news_updates');
        $notifications->product_announcements = $request->has('product_announcements');
        $notifications->maintenance_notifications = $request->has('maintenance_notifications');

        $notifications->save();

        return redirect()->route('profile.notifications', ['auth_token' => $request->query('auth_token')])
            ->with('success', 'Notification settings updated successfully!');
    }

    public function password()
    {
        $user = Auth::user();

        return view('profile.change-password', compact( 'user'));
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile.index', ['auth_token' => $request->query('auth_token')])
            ->with('success', 'Password updated successfully!');
    }

    public function logout()
    {
        Auth::logout(auth()->user());

        \DB::table('user_access_tokens')->where('user_id',auth()->user()->id)->delete();

        Http::get(env('URL_FRONT').'/api/logout', [
            'user_id' => auth()->id(),
        ]);

        return redirect('/');
    }
}
