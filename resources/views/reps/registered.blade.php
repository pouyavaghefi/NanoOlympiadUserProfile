@extends('layouts.master')

@section('main-body')
    <div class="main px-lg-4 px-md-4" x-data="userTable()" x-init="init()">
        @include('layouts.includes.overalls.header')

        <div class="container mt-4">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Registered Users</h5>
                    <input type="text"
                           class="form-control w-50"
                           placeholder="Search by name..."
                           x-model="search"
                           @input.debounce.300ms="filterUsers"
                           style="max-width: 300px;">
                </div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <h6 class="mb-2"><strong>Tips</strong></h6>
                        <ul class="mb-0 ps-3">
                            <li>User <strong>account status</strong> become active after changing the temporary password by the associated user.</li>
                        </ul>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Activation Sent</th>
                                <th>Admin Approval</th>
                                <th>User Approval</th>
                                <th>Registered At</th>
                                <th>Temp Password</th>
                                <th>Account Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template x-for="(user, index) in filtered" :key="user.id">
                                <tr>
                                    <td x-text="index + 1"></td>
                                    <td x-text="`${user.fname} ${user.lname}`"></td>
                                    <td x-text="user.email"></td>
                                    <td>
                                      <span class="badge"
                                            :class="user.token ? 'bg-success' : 'bg-secondary'"
                                            x-text="user.token ? 'true' : ''">

                                      </span>
                                    </td>
                                    <td>
                                        <span class="badge"
                                              :class="{
                                                  'bg-success': user.confirmed_by_admin,
                                                  'bg-secondary': !user.confirmed_by_admin
                                              }"
                                              x-text="user.confirmed_by_admin ? 'Approved' : 'Pending'"></span>
                                    </td>
                                    <td>
                                        <span class="badge"
                                              :class="{
                                                  'bg-success': user.confirmed_by_user,
                                                  'bg-secondary': !user.confirmed_by_user
                                              }"
                                              x-text="user.confirmed_by_user ? 'Confirmed' : 'Pending'"></span>
                                    </td>
                                    <td x-text="formatDate(user.created_at)"></td>
                                    <td>
                                        <div class="input-group">
                                            <template x-if="user.password">
                                                <input :type="user.showPassword ? 'text' : 'password'"
                                                       class="form-control form-control-sm"
                                                       readonly
                                                       :value="user.password">
                                            </template>
                                            <template x-if="!user.password">
                                                <input type="text"
                                                       class="form-control form-control-sm"
                                                       readonly
                                                       value="pass changed">
                                            </template>

                                            <button type="button"
                                                    class="btn btn-outline-secondary btn-sm"
                                                    @click="user.showPassword = !user.showPassword"
                                                    :disabled="!user.password">
                                                <i :class="user.showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                                            </button>
                                        </div>
                                    </td>

                                    <td>
                                        <span :class="{
                                            'badge bg-success': user.user_status == 1,
                                            'badge bg-warning': user.user_status == 0,
                                            'badge bg-danger': user.user_status == 2
                                        }" x-text="statusText(user.user_status)"></span>
                                    </td>
                                    <td>
                                        <template x-if="user.user_status == 1">
                                            <button class="btn btn-sm btn-outline-primary" @click="sendMessage(user)">
                                                <i class="bi bi-envelope"></i> Message
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="filtered.length === 0">
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">No users found.</td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function userTable() {
            return {
                users: @json($users),
                filtered: [],
                search: '',

                init() {
                    // Initialize filtered data
                    this.filtered = [...this.users];

                    // Ensure all users have the required properties
                    this.users = this.users.map(user => ({
                        ...user,
                        fname: user.fname || '',
                        lname: user.lname || '',
                        email: user.email || ''
                    }));
                },

                filterUsers() {
                    if (!this.search) {
                        this.filtered = [...this.users];
                        return;
                    }

                    const term = this.search.toLowerCase().trim();
                    this.filtered = this.users.filter(user => {
                        const fullName = `${user.fname} ${user.lname}`.toLowerCase();
                        const email = user.email.toLowerCase();
                        return fullName.includes(term) || email.includes(term);
                    });
                },

                formatDate(dateStr) {
                    if (!dateStr) return 'N/A';
                    try {
                        const options = { year: 'numeric', month: 'short', day: 'numeric' };
                        return new Date(dateStr).toLocaleDateString(undefined, options);
                    } catch (e) {
                        return 'Invalid date';
                    }
                },

                statusText(status) {
                    switch (status) {
                        case 1: return 'Approved';
                        case 2: return 'Rejected';
                        default: return 'Pending';
                    }
                },

                sendMessage(user) {
                    // Implement your message sending logic here
                    alert(`Coming Soon`);
                }
            }
        }
    </script>
@endsection