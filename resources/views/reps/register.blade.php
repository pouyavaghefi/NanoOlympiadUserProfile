@extends('layouts.master')

@section('main-body')
    <div class="main px-lg-4 px-md-4">
        @include('layouts.includes.overalls.header')

        <div class="container mt-4">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Register New Users</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <h6 class="mb-2"><strong>Guidelines for Registering New Users Using Form Fields</strong></h6>
                        <ul class="mb-0 ps-3">
                            <li>Users will be prompted to change their password after their first login.</li>
                            <li>Admin approval required.</li>
                            <li>A <strong>welcome message</strong> with your account credentials will be emailed after admin approval.</li>
                        </ul>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any()))
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

                    <form
                            method="POST"
                            action="{{ route('rep.reg.submit', ['auth_token' => request('auth_token')]) }}"
                            enctype="multipart/form-data"
                            x-data="userForm()"
                            x-init="init({
                            serverErrors: @js($errors->getMessages()),
                            oldUsers: @js(old('users', [['first_name' => '', 'last_name' => '', 'email' => '']]))
                        })"
                            @submit.prevent="showPasswordDialog">

                        @csrf
                        <div class="card shadow-lg border-0 rounded-4 p-4">
                            <h4 class="mb-4 text-center text-primary">Register Multiple Users</h4>

                            <!-- User Blocks -->
                            <template x-for="(user, index) in users" :key="index">
                                <div class="border rounded-3 p-3 mb-3 position-relative bg-light">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <input
                                                        type="text"
                                                        :name="`users[${index}][first_name]`"
                                                        class="form-control"
                                                        :class="{ 'is-invalid': hasError(index, 'first_name') }"
                                                        placeholder="First Name"
                                                        x-model="user.first_name"
                                                        @blur="validateField(index, 'first_name')"
                                                        required>
                                                <label>First Name</label>
                                                <template x-if="hasError(index, 'first_name')">
                                                    <div class="invalid-feedback d-block mt-1" x-text="getError(index, 'first_name')"></div>
                                                </template>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <input
                                                        type="text"
                                                        :name="`users[${index}][last_name]`"
                                                        class="form-control"
                                                        :class="{ 'is-invalid': hasError(index, 'last_name') }"
                                                        placeholder="Last Name"
                                                        x-model="user.last_name"
                                                        @blur="validateField(index, 'last_name')"
                                                        required>
                                                <label>Last Name</label>
                                                <template x-if="hasError(index, 'last_name')">
                                                    <div class="invalid-feedback d-block mt-1" x-text="getError(index, 'last_name')"></div>
                                                </template>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <input
                                                        type="email"
                                                        :name="`users[${index}][email]`"
                                                        class="form-control"
                                                        :class="{ 'is-invalid': hasError(index, 'email') }"
                                                        placeholder="Email Address"
                                                        x-model="user.email"
                                                        @blur="validateEmailField(index)"
                                                        required>
                                                <label>Email Address</label>
                                                <template x-if="hasError(index, 'email')">
                                                    <div class="invalid-feedback d-block mt-1" x-text="getError(index, 'email')"></div>
                                                </template>
                                                <template x-if="user.email && !hasError(index, 'email') && !justSubmitted">
                                                    <div class="text-success mt-1">✓ Email is available</div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                    <button
                                            type="button"
                                            class="btn btn-sm btn-danger mt-2 position-absolute top-0 end-0 m-2"
                                            @click="removeUser(index)"
                                            x-show="users.length > 1">
                                        Remove
                                    </button>
                                </div>
                            </template>

                            <!-- Add New User Button -->
                            <div class="text-start mt-3">
                                <button type="button" class="btn btn-outline-primary" @click="addUser">
                                    <i class="bi bi-plus-lg me-1"></i> Add Another User
                                </button>
                            </div>

                            <!-- Submit -->
                            <div class="text-end mt-4">
                                <button
                                        type="button"
                                        class="btn btn-success btn-lg px-4"
                                        @click="showPasswordDialog"
                                        :disabled="isSubmitting">
                                    <span x-show="!isSubmitting">
                                        <i class="bi bi-person-check-fill me-2"></i> Submit All
                                    </span>
                                    <span x-show="isSubmitting" class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function userForm() {
            return {
                users: [{ first_name: '', last_name: '', email: '' }],
                errors: {},
                isSubmitting: false,
                justSubmitted: false,
                commonPassword: '',

                init({ serverErrors, oldUsers }) {
                    if (oldUsers && oldUsers.length > 0) {
                        this.users = oldUsers;
                    }

                    if (serverErrors && Object.keys(serverErrors).length > 0) {
                        this.justSubmitted = true;
                        for (const key in serverErrors) {
                            const formattedKey = key.replace(/\.(\d+)\./, '[$1].');
                            this.errors[formattedKey] = serverErrors[key][0];
                        }
                    }
                },

                showPasswordDialog() {
                    if (!this.validateAll()) {
                        return;
                    }

                    Swal.fire({
                        title: 'Set Common Password for All Users',
                        html: `
                        <div class="mb-3 position-relative">
                            <input type="password" id="swal-password" class="form-control" placeholder="Enter password" autocomplete="new-password">
                            <i class="bi bi-eye-slash position-absolute end-0 top-50 translate-middle-y me-3"
                               style="cursor: pointer;"
                               onclick="toggleSwalPasswordVisibility()"></i>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="generateSwalPassword()">
                                Generate Password
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="copySwalPassword()">
                                Copy Password
                            </button>
                        </div>
                    `,
                        showCancelButton: true,
                        confirmButtonText: 'Submit All Users',
                        cancelButtonText: 'Cancel',
                        focusConfirm: false,
                        preConfirm: () => {
                            const password = document.getElementById('swal-password').value;
                            if (!password) {
                                Swal.showValidationMessage('Please enter or generate a password');
                                return false;
                            }
                            if (password.length < 8) {
                                Swal.showValidationMessage('Password must be at least 8 characters');
                                return false;
                            }
                            return password;
                        },
                        didOpen: () => {
                            // Generate a password by default
                            generateSwalPassword();
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.commonPassword = result.value;
                            this.submitForm();
                        }
                    });
                },

                generatePassword() {
                    const length = 12;
                    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
                    let password = "";

                    for (let i = 0; i < length; i++) {
                        password += charset.charAt(Math.floor(Math.random() * charset.length));
                    }

                    return password;
                },

                submitForm() {
                    this.isSubmitting = true;

                    // Create a hidden form with all data
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('rep.reg.submit', ['auth_token' => request('auth_token')]) }}";
                    form.enctype = 'multipart/form-data';

                    // Add CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('input[name="_token"]').value;
                    form.appendChild(csrfInput);

                    // Add common password
                    const passwordInput = document.createElement('input');
                    passwordInput.type = 'hidden';
                    passwordInput.name = 'common_password';
                    passwordInput.value = this.commonPassword;
                    form.appendChild(passwordInput);

                    // Add all users
                    this.users.forEach((user, index) => {
                        for (const key in user) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = `users[${index}][${key}]`;
                            input.value = user[key];
                            form.appendChild(input);
                        }
                    });

                    document.body.appendChild(form);
                    form.submit();
                },

                // ... keep all other existing methods ...
                addUser() {
                    this.users.push({ first_name: '', last_name: '', email: '' });
                },

                removeUser(index) {
                    this.users.splice(index, 1);
                    this.cleanUpErrors(index);
                },

                cleanUpErrors(index) {
                    for (const key in this.errors) {
                        if (key.includes(`[${index}]`)) {
                            delete this.errors[key];
                        }
                    }
                },

                hasError(index, field) {
                    return this.errors[`users[${index}].${field}`] !== undefined;
                },

                getError(index, field) {
                    return this.errors[`users[${index}].${field}`];
                },

                validateEmail(email) {
                    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return re.test(email);
                },

                validateField(index, field) {
                    if (!this.users[index][field]) {
                        this.errors[`users[${index}].${field}`] = `${field.replace('_', ' ')} is required`;
                    } else {
                        delete this.errors[`users[${index}].${field}`];
                    }
                },

                validateEmailField(index) {
                    if (!this.users[index].email) {
                        this.errors[`users[${index}].email`] = 'Email is required';
                        return;
                    }

                    if (!this.validateEmail(this.users[index].email)) {
                        this.errors[`users[${index}].email`] = 'Please enter a valid email address';
                    } else {
                        delete this.errors[`users[${index}].email`];
                    }
                },

                validateAll() {
                    let isValid = true;

                    this.users.forEach((user, index) => {
                        if (!user.first_name) {
                            this.errors[`users[${index}].first_name`] = 'First name is required';
                            isValid = false;
                        }

                        if (!user.last_name) {
                            this.errors[`users[${index}].last_name`] = 'Last name is required';
                            isValid = false;
                        }

                        if (!user.email) {
                            this.errors[`users[${index}].email`] = 'Email is required';
                            isValid = false;
                        } else if (!this.validateEmail(user.email)) {
                            this.errors[`users[${index}].email`] = 'Please enter a valid email';
                            isValid = false;
                        }
                    });

                    return isValid;
                }
            };
        }

        // Helper functions for SweetAlert dialog
        function toggleSwalPasswordVisibility() {
            const input = document.getElementById('swal-password');
            const icon = event.target;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        }

        function generateSwalPassword() {
            const length = 12;
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
            let password = "";

            for (let i = 0; i < length; i++) {
                password += charset.charAt(Math.floor(Math.random() * charset.length));
            }

            document.getElementById('swal-password').value = password;
        }

        function copySwalPassword() {
            const passwordInput = document.getElementById('swal-password');
            passwordInput.select();
            document.execCommand('copy');

            // Show feedback
            const copyButton = event.target;
            copyButton.textContent = 'Copied!';
            setTimeout(() => {
                copyButton.textContent = 'Copy Password';
            }, 2000);
        }
    </script>
@endsection