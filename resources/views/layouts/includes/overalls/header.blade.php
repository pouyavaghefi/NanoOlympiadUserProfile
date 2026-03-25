@php
    use Illuminate\Support\Facades\DB;

    $user = auth()->user();

    $avatarUrl = $user && $user->avatar
        ? route('private.file', [
            'type' => 'users',
            'userId' => $user->id,
            'filename' => basename($user->avatar),
          ])
        : asset('/assets/img/profile.png');

    $token = request()->query('auth_token');

    $unreadCount = auth()->check()
        ? DB::table('message_recipient')
            ->where('user_id', $user->id)
            ->where('read', 0)
            ->count()
        : 0;
@endphp

<div class="header sticky-top">
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm">
        <div class="container-fluid">
            <!-- IP Address (Left Side - Always Visible) -->
            <div class="d-flex align-items-center">
                <span class="text-muted small" title="Your current IP address">
                    <i class="icofont-location-pin me-1"></i> {{ request()->ip() }}
                </span>
            </div>

            <!-- Mobile Toggle Button (Right Side) -->
            <button class="navbar-toggler ms-auto" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Right Side User Controls -->
            <div class="d-flex align-items-center ms-lg-auto">
                <!-- Messages Dropdown -->
                <div class="dropdown position-relative me-3">
                    <a class="nav-link dropdown-toggle text-muted {{ $unreadCount > 0 ? 'pulse' : '' }}"
                       href="#" role="button" data-bs-toggle="dropdown"
                       aria-expanded="false" title="Messages">
                        <i class="icofont-envelope fs-5"></i>
                        @if($unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $unreadCount }}
                                <span class="visually-hidden">unread messages</span>
                            </span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0 dropdown-animation">
                        <div class="card border-0" style="min-width: 300px;">
                            <div class="card-header bg-primary text-white d-flex justify-content-between">
                                <span>Messages</span>
                                <a href="{{ route('profile.msg.inbox', ['auth_token' => $token]) }}" class="text-white small">View All</a>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item text-muted text-center small">
                                    @if ($unreadCount === 0)
                                        No new messages
                                    @else
                                        You have {{ $unreadCount }} unread message{{ $unreadCount > 1 ? 's' : '' }}
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- User Profile Dropdown -->
                <div class="dropdown user-profile">
                    <a class="d-flex align-items-center text-decoration-none"
                       href="#" role="button" data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <div class="me-2 text-end d-none d-sm-block">
                            <p class="mb-0 fw-bold">{{ $user->fullName() }}</p>
                            <small class="text-muted">User Profile</small>
                        </div>
                        <img class="avatar rounded-circle img-thumbnail"
                             src="{{ $avatarUrl }}" alt="profile" width="40" height="40">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <div class="p-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <img class="avatar rounded-circle me-3"
                                     src="{{ $avatarUrl }}" alt="profile" width="50" height="50">
                                <div>
                                    <p class="mb-0 fw-bold">{{ $user->fullName() }}</p>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('logout', ['auth_token' => $token]) }}"
                               class="list-group-item list-group-item-action border-0 text-danger">
                                <i class="icofont-logout me-2"></i>Sign Out
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>

<style>
    /* Custom Styles */
    .avatar {
        object-fit: cover;
        transition: all 0.3s ease;
    }
    .avatar:hover {
        transform: scale(1.05);
    }
    .dropdown-menu {
        min-width: 280px;
    }
    .pulse {
        position: relative;
    }
    .pulse::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background: rgba(255, 82, 82, 0.3);
        border-radius: 50%;
        animation: pulse 1.5s infinite;
        z-index: -1;
    }
    @keyframes pulse {
        0% { transform: scale(0.8); opacity: 1; }
        70% { transform: scale(1.3); opacity: 0; }
        100% { transform: scale(0.8); opacity: 0; }
    }
</style>

<!-- Offcanvas Sidebar for Mobile -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="sidebarMenuLabel">Menu</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="menu-list flex-grow-1 mt-3">
            <li>
                <a class="m-link profile-link auth-required {{ request()->is('/') ? 'active' : '' }}" href="/">
                    <i class="icofont-ui-home"></i><span>&nbsp;&nbsp;Dashboard</span>
                </a>
            </li>

            <li class="auth-required" style="display: none;">
                <a class="m-link profile-link {{ request()->is('profile*') ? 'active' : '' }}"
                   href="{{ route('profile.index') }}">
                    <i class="icofont-user"></i><span>&nbsp;&nbsp;Profile</span>
                </a>
            </li>

            <li class="auth-required" style="display: none;">
                <a class="m-link profile-link {{ request()->is('courses*') ? 'active' : '' }}"
                   href="{{ url('courses') }}">
                    <i class="icofont-book"></i><span>&nbsp;&nbsp;Courses</span>
                </a>
            </li>

            <li class="auth-required" style="display: none;">
                <a class="m-link profile-link no-token {{ request()->is('home-website') ? 'active' : '' }}"
                   href="{{ env('URL_FRONT') }}">
                    <i class="icofont-globe"></i><span>&nbsp;&nbsp;Website Home</span>
                </a>
            </li>
        </ul>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Bootstrap tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Handle auth token for mobile menu links
        const urlParams = new URLSearchParams(window.location.search);
        const authToken = urlParams.get('auth_token');

        if (authToken) {
            document.querySelectorAll('.auth-required').forEach(el => {
                el.style.display = 'block';
            });

            document.querySelectorAll('.profile-link').forEach(link => {
                if (link.classList.contains('no-token')) return; // skip

                const href = link.getAttribute('href');
                if (href && !href.includes('auth_token')) {
                    link.setAttribute('href', `${href}?auth_token=${authToken}`);
                }
            });
        }
    });
</script>