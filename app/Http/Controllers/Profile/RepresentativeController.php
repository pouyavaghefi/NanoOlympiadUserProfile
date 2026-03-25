<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempUser;
use Carbon\Carbon;
use DB;
use Mail;
use App\Mail\ActivationMail;
use App\Mail\WelcomeUserMail;
use Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RepresentativeController extends Controller
{
    public function register()
    {
        return view('reps.register');
    }

    protected function customAttributes(array $users): array
    {
        $attributes = [];

        foreach ($users as $index => $user) {
            $num = $index + 1;
            $attributes["users.{$index}.first_name"] = "First Name for user #{$num}";
            $attributes["users.{$index}.last_name"] = "Last Name for user #{$num}";
            $attributes["users.{$index}.email"] = "Email Address for user #{$num}";
        }

        return $attributes;
    }

    public function doRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'users.*.first_name' => 'required|string|max:255',
            'users.*.last_name' => 'required|string|max:255',
            'users.*.email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
                'unique:temp_users,email',
            ],
            'common_password' => 'required|string|min:8',
        ], [
            'common_password.min' => 'The password must be at least 8 characters.',
            'common_password.required' => 'A password is required for all users.',
        ], $this->customAttributes($request->input('users')));

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $submittedBy = auth()->check() ? auth()->id() : null;
        $ipAddress = $request->ip();
        $now = Carbon::now();
        $commonPassword = Hash::make($request->input('common_password'));

        $tempUsers = collect($request->input('users'))->map(function ($user) use ($submittedBy, $ipAddress, $now, $commonPassword, $request) {

            $userData = [
                'fname' => $user['first_name'],
                'lname' => $user['last_name'],
                'email' => $user['email'],
                'password' => $request->input('common_password'),
                'password_hashed' => $commonPassword,
                'activation_type' => 'no_activation',
                'user_status' => 0,
                'submitted_by' => $submittedBy,
                'token' => null,
                'notes' => null,
                'ip_address' => $ipAddress,
                'submitted_at' => $now,
                'confirmed_by_admin' => false,
                'confirmed_by_admin_at' => null,
                'confirmed_by_user' => true,
                'confirmed_by_user_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            Mail::to($user['email'])->send(new WelcomeUserMail($userData));

            return $userData;
        })->toArray();

        TempUser::insert($tempUsers);

        return redirect()->back()->with('success', 'Users submitted successfully and awaiting admin approval.');
    }

    public function allRegisteredUsers()
    {
        $users = TempUser::where('submitted_by', auth()->id())->get();
        return view('reps.registered', compact('users'));
    }

    public function bulkText()
    {
        return view('reps.register-text');
    }

    public function bulkTextSubmit(Request $request)
    {
        $rawInput = $request->input('bulk_users');
        $lines = preg_split('/\r\n|\r|\n/', trim($rawInput));
        $users = [];

        foreach ($lines as $line) {
            $parts = array_map('trim', explode(',', $line));
            if (count($parts) === 3) {
                [$fullName, $email, $password] = $parts;

                // Split full name into fname and lname
                $nameParts = explode(' ', $fullName, 2);
                $fname = $nameParts[0] ?? '';
                $lname = $nameParts[1] ?? '';

                $users[] = compact('fname', 'lname', 'email', 'password');
            }
        }

        // Validate parsed users
        foreach ($users as $i => $user) {
            $validator = Validator::make($user, [
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'nullable|string|min:6',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $submittedBy = auth()->id();
        $ip = $request->ip();
        $now = now();

        foreach ($users as $user) {
            $token = Str::random(40);

            DB::table('temp_users')->insert([
                'fname' => $user['fname'],
                'lname' => $user['lname'],
                'email' => $user['email'],
                'activation_type' => 'activation_sent',
                'confirmed_by_user' => 0,
                'confirmed_by_admin' => 1,
                'confirmed_by_admin_at' => now(),
                'user_status' => 0,
                'submitted_by' => $submittedBy,
                'token' => $token,
                'password' => $user['password'] ?? null,
                'password_hashed' => isset($user['password']) ? Hash::make($user['password']) : null,
                'ip_address' => $ip,
                'submitted_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Send activation email
            Mail::to($user['email'])->send(new ActivationMail($token, $user['fname']));
        }

        return redirect()->back()->with('success', 'Users registered successfully.');
    }
}