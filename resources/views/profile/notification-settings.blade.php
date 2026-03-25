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

                        <form action="{{ route('profile.set', [], false) }}?auth_token={{ request('auth_token') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3 form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="system_alerts" name="system_alerts"
                                        {{ old('system_alerts', $notifications->system_alerts ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="system_alerts">&nbsp;&nbsp;System Alerts</label>
                            </div>

                            <div class="mb-3 form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="news_updates" name="news_updates"
                                        {{ old('news_updates', $notifications->news_updates ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="news_updates">&nbsp;&nbsp;News Updates</label>
                            </div>

                            <div class="mb-3 form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="product_announcements" name="product_announcements"
                                        {{ old('product_announcements', $notifications->product_announcements ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="product_announcements">&nbsp;&nbsp;Product Announcements</label>
                            </div>

                            <div class="mb-3 form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="maintenance_notifications" name="maintenance_notifications"
                                        {{ old('maintenance_notifications', $notifications->maintenance_notifications ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="maintenance_notifications">&nbsp;&nbsp;Maintenance Notifications</label>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Save Preferences</button>
                                <a href="{{ route('profile.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
@endsection
