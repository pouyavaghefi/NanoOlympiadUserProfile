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

                        <form action="{{ route('profile.update', [], false) }}?auth_token={{ request('auth_token') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                {{-- Basic Info --}}
                                <div class="col-md-6 mb-3">
                                    <label for="fname" class="form-label">First Name</label>
                                    <input type="text" name="fname" class="form-control" value="{{ old('fname', $user->fname) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lname" class="form-label">Last Name</label>
                                    <input type="text" name="lname" class="form-control" value="{{ old('lname', $user->lname) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" readonly>
                                    <small class="form-text text-muted">This email address cannot be changed.</small>
                                </div>


                                {{-- Profile Picture --}}
                                <div class="col-md-6 mb-4">
                                    <label for="avatar" class="form-label fw-bold">Profile Picture</label>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @php
                                                // Passport photo in members folder
                                                if ($detail->passport_photo) {
                                                    $passportFilename = basename($detail->passport_photo);
                                                    $passportUrl = route('private.file', [
                                                        'type' => 'members',
                                                        'userId' => auth()->user()->id,
                                                        'filename' => $passportFilename,
                                                    ]);
                                                } else {
                                                    $passportUrl = asset('assets/img/passport-placeholder.jpg');
                                                }

                                                // Avatar photo in users folder
                                                if ($user->avatar) {
                                                    $avatarFilename = basename($user->avatar);
                                                    $avatarUrl = route('private.file', [
                                                        'type' => 'users',
                                                        'userId' => $user->id,
                                                        'filename' => $avatarFilename,
                                                    ]);
                                                } else {
                                                    $avatarUrl = asset('/assets/img/profile.png');
                                                }
                                            @endphp

                                            <img id="avatarPreview" src="{{ $avatarUrl }}" alt="User Avatar" class="rounded-circle border" style="width: 100px; height: 100px; object-fit: cover;">
                                        </div>
                                        <div class="flex-grow-1">
                                            <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" id="avatar" accept="image/*" onchange="previewAvatar(this)">
                                            @error('avatar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Accepted: JPG, PNG. Max size: 2MB</small>
                                        </div>
                                    </div>
                                </div>

                                <hr class="mt-4 mb-3 w-100">

                                {{-- Extended Personal Info --}}
                                <div class="col-md-6 mb-3">
                                    <label for="surname" class="form-label">Surname</label>
                                    <input type="text" name="surname" class="form-control" value="{{ old('surname', $detail->surname ?? '') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="father_name" class="form-label">Father's Name</label>
                                    <input type="text" name="father_name" class="form-control" value="{{ old('father_name', $detail->father_name ?? '') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="birthday" class="form-label">Birthday</label>
                                    <input type="date" name="birthday" class="form-control" value="{{ old('birthday', $detail->birthday ?? '') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="passport_number" class="form-label">Passport Number</label>
                                    <input type="text" name="passport_number" class="form-control" value="{{ old('passport_number', $detail->passport_number ?? '') }}">
                                </div>

                                {{-- Passport Photo --}}
                                <div class="col-md-6 mb-4">
                                    <label for="passport_photo" class="form-label fw-bold">Upload Passport Photo
                                        <span class="text-muted d-block" style="font-size: 0.9em;">(JPEG, PNG - Max: 2MB)</span>
                                    </label>
                                    <div class="border rounded p-3 position-relative text-center bg-light" style="min-height: 180px;">

                                        <img id="passportPreview" src="{{ $passportUrl }}" alt="Passport Photo" class="rounded-circle img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                                        <input type="file" name="passport_photo" class="form-control mt-3 @error('passport_photo') is-invalid @enderror" id="passport_photo" accept="image/*" onchange="previewPassportPhoto(this)">
                                        @error('passport_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Contact Info --}}
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="male" {{ old('gender', $detail->gender ?? '') === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $detail->gender ?? '') === 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $detail->phone ?? '') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    @include('layouts.includes.inputs.select-country')
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" name="city" class="form-control" value="{{ old('city', $detail->city ?? '') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea name="address" class="form-control" rows="2">{{ old('address', $detail->address ?? '') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="postal_code" class="form-label">Postal Code</label>
                                    <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $detail->postal_code ?? '') }}">
                                </div>

                                {{-- Referral Info --}}
                                <div class="col-md-6 mb-3">
                                    <label for="referer_code" class="form-label">Referer Code</label>
                                    <input type="text" name="referer_code" class="form-control" value="{{ old('referer_code', $detail->referer_code ?? '') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="agent_name" class="form-label">Agent Name</label>
                                    <input type="text" name="agent_name" class="form-control" value="{{ old('agent_name', $detail->agent_name ?? '') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="agent_code" class="form-label">Agent Code</label>
                                    <input type="text" name="agent_code" class="form-control" value="{{ old('agent_code', $detail->agent_code ?? '') }}">
                                </div>

                                {{-- Private Files --}}
                                @if(!empty($files) && count($files) > 0)
                                    <div class="col-12 mb-4">
                                        <label class="form-label fw-bold">My Private Files</label>
                                        <ul class="list-group">
                                            @foreach($files as $filename)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>{{ $filename }}</span>
                                                    <a href="{{ route('private.file', ['type' => 'users', 'userId' => $user->id, 'filename' => $filename]) }}"
                                                       target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary">
                                                        Download
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            {{-- Submit --}}
                            <div class="mt-4">
                                <button type="submit" class="btn btn-success">Update Profile</button>
                                <a href="{{ route('profile.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>

                            {{-- Scripts --}}
                            <script>
                                function previewAvatar(input) {
                                    if (input.files && input.files[0]) {
                                        const reader = new FileReader();
                                        reader.onload = function (e) {
                                            document.getElementById('avatarPreview').src = e.target.result;
                                        };
                                        reader.readAsDataURL(input.files[0]);
                                    }
                                }

                                function previewPassportPhoto(input) {
                                    if (input.files && input.files[0]) {
                                        const reader = new FileReader();
                                        reader.onload = function (e) {
                                            document.getElementById('passportPreview').src = e.target.result;
                                        };
                                        reader.readAsDataURL(input.files[0]);
                                    }
                                }
                            </script>
                        </form>

                </div>
            </div>
        </div>
    </div>
@endsection