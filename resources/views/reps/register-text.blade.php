@extends('layouts.master')

@section('main-body')
    <div class="main px-lg-4 px-md-4" x-data="bulkUserForm()" x-init="init()">
        @include('layouts.includes.overalls.header')

        <div class="container mt-4">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Bulk User Registration</h5>
                </div>

                <form method="POST" action="{{ route('rep.bulk-text.submit', ['auth_token' => request('auth_token')]) }}">
                    @csrf

                    <div class="card-body">
                        <div class="alert alert-info" role="alert">
                            <h6 class="mb-2"><strong>Guidelines for Registering New Users Using Bulk Text</strong></h6>
                            <ul class="mb-0 ps-3">
                                <li>Users will be prompted to change their password after their first login.</li>
                                <li>Admin approval is <span style="color:purple">not required</span> using this method.</li>
                                <li>An <strong>activation link</strong> will be emailed, and the account will be activated upon clicking the link (no account info will be provided).</li>
                            </ul>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="bulk_users" class="form-label fw-bold">Bulk Users (One per line)</label>
                            <textarea id="bulk_users"
                                      name="bulk_users"
                                      class="form-control"
                                      rows="8"
                                      placeholder="Example: John Doe, john@example.com, SecretPass123"
                                      x-model="rawInput"
                                      @input.debounce.300ms="parseUsers">{{ old('bulk_users') }}</textarea>
                            <small class="form-text text-muted">Format: <code>Full Name, Email, Password</code> per line</small>
                        </div>

                        <div class="mb-3">
                            <h6>Parsed Users Preview</h6>
                            <ul class="list-group">
                                <template x-for="(u, i) in users" :key="i">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span x-text="`${u.name} (${u.email})`"></span>
                                        <span class="badge bg-secondary" x-text="u.password ? '✓ Password' : '⚠️ Missing password'"></span>
                                    </li>
                                </template>
                                <template x-if="users.length === 0">
                                    <li class="list-group-item text-muted">No valid entries yet.</li>
                                </template>
                            </ul>
                        </div>

                            <div class="text-end d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary"
                                        @click="saveDraft">
                                    Save as Draft
                                </button>

                                <button type="submit" class="btn btn-success">Register Users</button>
                            </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function bulkUserForm() {
            return {
                rawInput: @json(session('success') ? '' : old('bulk_users', '')),
                users: [],

                init() {
                    // Load saved draft if no old input (like on first page load or after success)
                    const draft = localStorage.getItem('bulk_users_draft');
                    if (draft && !this.rawInput.trim()) {
                        this.rawInput = draft;
                    }
                    this.parseUsers();
                },

                parseUsers() {
                    this.users = [];
                    if (!this.rawInput.trim()) return;

                    const lines = this.rawInput.trim().split('\n');
                    for (let line of lines) {
                        let parts = line.split(',');
                        if (parts.length === 3) {
                            const fullName = parts[0].trim();
                            const email = parts[1].trim();
                            const password = parts[2].trim();

                            this.users.push({
                                name: fullName,
                                email: email,
                                password: password,
                            });
                        }
                    }
                },

                saveDraft() {
                    localStorage.setItem('bulk_users_draft', this.rawInput);
                    alert('Draft saved locally.');
                },

                clearDraft() {
                    localStorage.removeItem('bulk_users_draft');
                }
            };
        }
    </script>

@endsection
