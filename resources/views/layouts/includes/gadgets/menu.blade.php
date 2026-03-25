<ul class="menu-list flex-grow-1 mt-3">
    <li>
        <a class="m-link profile-link auth-required {{ request()->is('/') ? 'active' : '' }}" href="/">
            <i class="icofont-ui-home"></i><span>&nbsp;&nbsp;Dashboard</span>
        </a>
    </li>

    <li class="collapsed auth-required" style="display: none;">
        <a class="m-link profile-link {{ request()->is('profile*') && !request()->is('profile/messages*') ? 'active' : '' }}"
           data-bs-toggle="collapse" data-bs-target="#menu-Profile" href="#">
            <i class="icofont-user"></i><span>Profile</span>
            <span class="arrow icofont-dotted-down ms-auto text-end fs-5"></span>
        </a>
        <ul class="sub-menu collapse {{ request()->is('profile*') && !request()->is('profile/messages*') ? 'show' : '' }}" id="menu-Profile">
            <li>
                <a class="ms-link profile-link {{ request()->routeIs('profile.index') ? 'active' : '' }}" href="{{ route('profile.index') }}">
                    <span>Overview</span>
                </a>
            </li>
            <li>
                <a class="ms-link profile-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                    <span>Edit Profile</span>
                </a>
            </li>
            <li>
                <a class="ms-link profile-link {{ request()->routeIs('profile.settings') ? 'active' : '' }}" href="{{ route('profile.settings') }}">
                    <span>Account Settings</span>
                </a>
            </li>
            <li>
                <a class="ms-link profile-link {{ request()->routeIs('profile.security') ? 'active' : '' }}" href="{{ route('profile.security') }}">
                    <span>Security</span>
                </a>
            </li>
            <li>
                <a class="ms-link profile-link {{ request()->routeIs('profile.notifications') ? 'active' : '' }}" href="{{ route('profile.notifications') }}">
                    <span>Notifications</span>
                </a>
            </li>
            <li>
                <a class="ms-link profile-link {{ request()->routeIs('profile.password') ? 'active' : '' }}" href="{{ route('profile.password') }}">
                    <span>Change Password</span>
                </a>
            </li>
        </ul>
    </li>

    @php
        use Illuminate\Support\Facades\DB;

        $unreadCount = auth()->check()
            ? DB::table('message_recipient')
                ->where('user_id', auth()->id())
                ->where('read', 0)
                ->count()
            : 0;

        $messagesActive = request()->is('profile/messages*');
    @endphp

    <li class="collapsed auth-required" style="display: none;">
        <a class="m-link profile-link {{ $messagesActive ? 'active' : '' }} {{ $unreadCount > 0 ? 'animate-unread' : '' }}"
           data-bs-toggle="collapse" data-bs-target="#menu-Messages" href="#">
            <i class="icofont-envelope"></i><span>Messages</span>
            <span class="arrow icofont-dotted-down ms-auto text-end fs-5"></span>
        </a>

        <ul class="sub-menu collapse {{ $messagesActive ? 'show' : '' }}" id="menu-Messages">
            <li>
                <a class="ms-link profile-link {{ request()->routeIs('profile.msg.inbox') ? 'active' : '' }}"
                   href="{{ route('profile.msg.inbox') }}">
                    <span>Inbox</span>
                    @if($unreadCount > 0)
                        <span class="badge bg-danger ms-2">{{ $unreadCount }}</span>
                    @endif
                </a>
            </li>
            <li>
                <a class="ms-link profile-link {{ request()->routeIs('profile.msg.sent') ? 'active' : '' }}"
                   href="{{ route('profile.msg.sent') }}">
                    <span>Sent</span>
                </a>
            </li>
            <li>
                <a class="ms-link profile-link {{ request()->routeIs('profile.msg.send') ? 'active' : '' }}"
                   href="{{ route('profile.msg.send') }}">
                    <span>Compose</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="collapsed auth-required" style="display: none;">
        <a class="m-link profile-link {{ request()->is('courses*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#menu-Courses" href="#">
            <i class="icofont-certificate"></i><span>Courses</span>
            <span class="arrow icofont-dotted-down ms-auto text-end fs-5"></span>
        </a>
        <ul class="sub-menu collapse {{ request()->is('courses*') ? 'show' : '' }}" id="menu-Courses">
            <li><a class="ms-link profile-link {{ request()->routeIs('courses.index') ? 'active' : '' }}" href="{{ route('courses.index') }}"><span>All Courses</span></a></li>
{{--            <li><a class="ms-link profile-link {{ request()->routeIs('courses.free') ? 'active' : '' }}" href="{{ route('courses.free') }}"><span>Free Courses</span></a></li>--}}
{{--            <li><a class="ms-link profile-link {{ request()->routeIs('courses.premium') ? 'active' : '' }}" href="{{ route('courses.premium') }}"><span>Premium Courses</span></a></li>--}}
{{--            <li><a class="ms-link profile-link {{ request()->routeIs('courses.index') ? 'active' : '' }}" href="{{ route('courses.index') }}"><span>All Categories</span></a></li>--}}
            <li><a class="ms-link profile-link {{ request()->routeIs('courses.enrolled') ? 'active' : '' }}" href="{{ route('courses.enrolled') }}"><span>My Enrollments</span></a></li>
{{--            <li><a class="ms-link profile-link {{ request()->routeIs('courses.enrolled') ? 'active' : '' }}" href=""><span>Teachers</span></a></li>--}}
{{--            <li><a class="ms-link profile-link {{ request()->routeIs('courses.progress') ? 'active' : '' }}" href="{{ route('courses.progress') }}"><span>My Progress</span></a></li>--}}
        </ul>
    </li>

    <li class="collapsed auth-required" style="display: none;">
        <a class="m-link profile-link {{ request()->is('rep*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#menu-Rep" href="#">
            <i class="icofont-users-alt-5"></i><span>User Control</span>
            <span class="arrow icofont-dotted-down ms-auto text-end fs-5"></span>
        </a>

        <ul class="sub-menu collapse {{ request()->is('rep*') ? 'show' : '' }}" id="menu-Rep">

            {{-- Manual Registration --}}
            <li>
                <a class="ms-link profile-link {{ request()->routeIs('rep.reg') ? 'active' : '' }}" href="{{ route('rep.reg') }}">
                    <span>Register User</span>
                </a>
            </li>

            {{-- Excel Upload --}}
{{--            <li>--}}
{{--                <a class="ms-link profile-link {{ request()->routeIs('rep.bulk-excel') ? 'active' : '' }}" href="{{ route('rep.bulk-excel') }}">--}}
{{--                    <span>Bulk Upload via Excel</span>--}}
{{--                </a>--}}
{{--            </li>--}}

            {{-- Copy-Paste Bulk Entry --}}
            <li>
                <a class="ms-link profile-link {{ request()->routeIs('rep.bulk-text') ? 'active' : '' }}" href="{{ route('rep.bulk-text') }}">
                    <span>Bulk Text Registration</span>
                </a>
            </li>

            {{-- Referral Link / Invite --}}
{{--            <li>--}}
{{--                <a class="ms-link profile-link {{ request()->routeIs('rep.bulk-referral') ? 'active' : '' }}" href="{{ route('rep.bulk-referral') }}">--}}
{{--                    <span>Invite via Referral Link</span>--}}
{{--                </a>--}}
{{--            </li>--}}

            {{-- My Registered Users --}}
            <li>
                <a class="ms-link profile-link {{ request()->routeIs('rep.reg.all') ? 'active' : '' }}" href="{{ route('rep.reg.all') }}">
                    <span>My Registered Users</span>
                </a>
            </li>

        </ul>
    </li>

    {{--    <li class="collapsed auth-required" style="display: none;">--}}
{{--        <a class="m-link profile-link {{ request()->is('payments*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#menu-Payments" href="#">--}}
{{--            <i class="icofont-money"></i><span>Payments</span>--}}
{{--            <span class="arrow icofont-dotted-down ms-auto text-end fs-5"></span>--}}
{{--        </a>--}}
{{--        <ul class="sub-menu collapse {{ request()->is('payments*') ? 'show' : '' }}" id="menu-Payments">--}}
{{--            <li>--}}
{{--                <a class="ms-link profile-link {{ request()->routeIs('payments.history') ? 'active' : '' }}" href="">--}}
{{--                    <span>Payment History</span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li>--}}
{{--                <a class="ms-link profile-link {{ request()->routeIs('payments.invoices') ? 'active' : '' }}" href="">--}}
{{--                    <span>Invoices</span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li>--}}
{{--                <a class="ms-link profile-link {{ request()->routeIs('payments.methods') ? 'active' : '' }}" href="">--}}
{{--                    <span>Payment Methods</span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li>--}}
{{--                <a class="ms-link profile-link {{ request()->routeIs('payments.new') ? 'active' : '' }}" href="">--}}
{{--                    <span>Make a Payment</span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--        </ul>--}}
{{--    </li>--}}

{{--    <li class="collapsed auth-required" style="display: none;">--}}
{{--        <a class="m-link profile-link {{ request()->is('ticketing*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#menu-Ticketing" href="#">--}}
{{--            <i class="icofont-ticket"></i><span>Tickets</span>--}}
{{--            <span class="arrow icofont-dotted-down ms-auto text-end fs-5"></span>--}}
{{--        </a>--}}
{{--        <ul class="sub-menu collapse {{ request()->is('ticketing*') ? 'show' : '' }}" id="menu-Ticketing">--}}
{{--            <li>--}}
{{--                <a class="ms-link profile-link {{ request()->routeIs('ticketing.index') ? 'active' : '' }}" href="">--}}
{{--                    <span>All Tickets</span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li>--}}
{{--                <a class="ms-link profile-link {{ request()->routeIs('ticketing.create') ? 'active' : '' }}" href="">--}}
{{--                    <span>New Ticket</span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li>--}}
{{--                <a class="ms-link profile-link {{ request()->routeIs('ticketing.reports') ? 'active' : '' }}" href="">--}}
{{--                    <span>Reports</span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--        </ul>--}}
{{--    </li>--}}

{{--    <li class="auth-required" style="">--}}
{{--        <a class="m-link" href="">--}}
{{--            <i class="icofont-notepad" style="color: white;"></i> <span>Logs</span>--}}
{{--        </a>--}}
{{--    </li>--}}


    <li class="auth-required" style="display: none;">
        <a class="m-link" href="{{ route('logout') }}">
            <i class="icofont-logout"></i> <span>Logout</span>
        </a>
    </li>
</ul>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get the URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const authToken = urlParams.get("auth_token");

        if (authToken) {
            // Show elements that require authentication
            document.querySelectorAll(".auth-required").forEach(el => {
                el.style.display = "block";
            });

            // Append auth_token to profile-related links
            document.querySelectorAll(".profile-link").forEach(link => {
                const href = link.getAttribute("href");
                link.setAttribute("href", `${href}?auth_token=${authToken}`);
            });
        }
    });
</script>
