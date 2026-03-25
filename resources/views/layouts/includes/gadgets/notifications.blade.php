<div class="dropdown notifications zindex-popover">
    <a class="nav-link dropdown-toggle pulse" href="#" role="button" data-bs-toggle="dropdown">
        <i class="icofont-alarm fs-5"></i>
        <span class="pulse-ring"></span>
    </a>
    <div id="NotificationsDiv" class="dropdown-menu rounded-lg shadow border-0 dropdown-animation dropdown-menu-sm-end p-0 m-0">
        <div class="card border-0 w380">
            <div class="card-header border-0 p-3">
                <h5 class="mb-0 font-weight-light d-flex justify-content-between">
                    <span>Notifications</span>
                    <span class="badge text-white">{{ count($notifications) }}</span>
                </h5>
            </div>
            <div class="tab-content card-body">
                <div class="tab-pane fade show active">
                    <ul class="list-unstyled list mb-0">
                        @foreach($notifications as $notification)
                            <li class="py-2 mb-1 border-bottom">
                                <a href="javascript:void(0);" class="d-flex">
                                    <img class="avatar rounded-circle" @if($notification->type == "system") src="/assets/img/system.png" @else src="/assets/img/avatar.png" @endif alt="">
                                    <div class="flex-fill ms-2">
                                        <p class="d-flex justify-content-between mb-0 ">
                                            <span class="font-weight-bold">{{ $notification->title }}</span>
                                            <small>{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                        </p>
                                        <span class="">{{ $notification->message }} <span class="badge bg-success">{{ $notification->type }}</span></span>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            {{--            <a class="card-footer text-center border-top-0" href="#"> View all notifications</a>--}}
        </div>
    </div>
</div>