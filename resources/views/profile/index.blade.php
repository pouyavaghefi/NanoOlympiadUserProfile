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

                @php
                $avatar = auth()->user()->avatar;
                $user = auth()->user();

                $avatarUrl = $user && $user->avatar
                ? route('private.file', [
                'type' => 'users',
                'userId' => $user->id,
                'filename' => basename($user->avatar),
                ])
                : asset('/assets/img/profile.png');
                @endphp


                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        <img src="{{ $avatarUrl }}" class="img-fluid rounded-circle mb-3" style="width: 120px; height: 120px;" alt="Profile Picture">
                    </div>
                    <div class="col-md-9">
                        <table class="table table-borderless">
                            <tr>
                                <th>Name:</th>
                                <td>{{ Auth::user()->fullName() }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ Auth::user()->email }}</td>
                            </tr>
                            <tr>
                                <th>Role:</th>
                                <td>{{ Auth::user()->role ?? 'Student' }}</td>
                            </tr>
                            <tr>
                                <th>Member Since:</th>
                                <td>{{ Auth::user()->created_at }}</td>
                            </tr>
                        </table>
                        <a href="{{ route('profile.edit', ['auth_token' => request('auth_token')]) }}" class="btn btn-outline-primary mt-2">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
