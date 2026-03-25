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

                        <form action="{{ route('profile.apply', [], false) }}?auth_token={{ request('auth_token') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="language" class="form-label">Language Preference</label>
                                    <select name="language" class="form-select" disabled>
                                        @foreach($languages as $lang)
                                            <option value="{{ $lang->code }}" {{ old('language', $settings->language ?? '') == $lang->code ? 'selected' : '' }}>
                                                {{ $lang->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="timezone" class="form-label">Timezone</label>
                                    <select name="timezone" class="form-select">
                                        @foreach (timezone_identifiers_list() as $tz)
                                            <option value="{{ $tz }}" {{ old('timezone', $settings->timezone ?? '') == $tz ? 'selected' : '' }}>
                                                {{ $tz }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="date_format" class="form-label">Date Format</label>
                                    <select name="date_format" class="form-select">
                                        <option value="Y-m-d" {{ old('date_format', $settings->date_format ?? '') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                        <option value="d/m/Y" {{ old('date_format', $settings->date_format ?? '') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                        <option value="m/d/Y" {{ old('date_format', $settings->date_format ?? '') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                    </select>
                                </div>

                                <hr class="mt-4 mb-3">


                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-success">Update Profile</button>
                                <a href="{{ route('profile.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
@endsection
