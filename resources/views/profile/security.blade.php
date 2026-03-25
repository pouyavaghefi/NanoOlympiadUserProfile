@extends('layouts.master')

@section('main-body')
    <div class="main px-lg-4 px-md-4">
        @include('layouts.includes.overalls.header')

        <div class="container mt-4">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Edit Profile</h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>There were some problems with your input:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                        <form action="{{ route('profile.adjust', [], false) }}?auth_token={{ request('auth_token') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <h5 class="mb-3">Security Settings</h5>

                                {{-- Two-Factor Authentication --}}
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="two_factor_enabled" id="two_factor_enabled"
                                                {{ old('two_factor_enabled', $security->two_factor_enabled ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="two_factor_enabled">&nbsp;&nbsp;Enable Two-Factor Authentication</label>
                                    </div>
                                </div>

                                {{-- Email Notifications --}}
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="email_notifications" id="email_notifications"
                                                {{ old('email_notifications', $security->email_notifications ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email_notifications">&nbsp;&nbsp;Enable Email Notifications</label>
                                    </div>
                                </div>

                                {{-- Login Alerts --}}
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="login_alerts" id="login_alerts"
                                                {{ old('login_alerts', $security->login_alerts ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="login_alerts">&nbsp;&nbsp;Receive Login Alerts</label>
                                    </div>
                                </div>

                                {{-- Password Reset Allowed --}}
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="allow_password_reset" id="allow_password_reset"
                                                {{ old('allow_password_reset', $security->allow_password_reset ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="allow_password_reset">&nbsp;&nbsp;Allow Password Reset</label>
                                    </div>
                                </div>

                                {{-- Suspicious Login Protection --}}
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="suspicious_login_protection" id="suspicious_login_protection"
                                                {{ old('suspicious_login_protection', $security->suspicious_login_protection ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="suspicious_login_protection">&nbsp;&nbsp;Enable Suspicious Login Protection</label>
                                    </div>
                                </div>

                                {{-- Backup Email --}}
                                <div class="col-md-6 mb-3">
                                    <label for="backup_email" class="form-label">Backup Email</label>
                                    <input type="email" name="backup_email" class="form-control" id="backup_email"
                                           value="{{ old('backup_email', $security->backup_email ?? '') }}">
                                </div>

                                {{-- Trusted IP --}}
                                <div class="col-md-6 mb-3">
                                    <label for="trusted_ip" class="form-label">Trusted IP Address</label>
                                    <input type="text" name="trusted_ip" class="form-control" id="trusted_ip"
                                           value="{{ old('trusted_ip', $security->trusted_ip ?? '') }}">
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-success">Save Security Settings</button>
                                <a href="{{ route('profile.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>

                </div>
            </div>
        </div>
    </div>
@endsection
