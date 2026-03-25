<div class="h-right align-items-center mr-5 mr-lg-0 order-3 d-flex">
    <div class="dropdown user-profile ml-2 ml-sm-3 d-flex align-items-center zindex-popover">
        <div class="u-info me-2">
            <p class="mb-0 text-end line-height-sm text-white"><span class="font-weight-bold"></span></p>
            <small-xs class="text-white"></small-xs>
        </div>
        <a class="nav-link dropdown-toggle pulse p-0" href="#" role="button" data-bs-toggle="dropdown" data-bs-display="static">
            <img class="avatar lg rounded-circle img-thumbnail" src="/course_player/img/user.png" alt="profile">
        </a>

        <div class="dropdown-menu rounded-lg shadow border-0 dropdown-animation dropdown-menu-end p-0 m-0">
            <div class="card border-0 w280">
                <div class="card-body pb-0">
                    <div class="d-flex py-1">
                        <img class="avatar rounded-circle" src="/course_player/img/user.png" alt="profile">
                        <div class="flex-fill ms-3">
                            <p class="mb-0"><span class="font-weight-bold">@if(auth()->check()) {{ auth()->user()->fullName() }} @else guest @endif</span></p>
                            @if(auth()->check())<small class=""> {{ auth()->user()->email }}  </small> @else @endif
                        </div>
                    </div>
                    <div><hr class="dropdown-divider border-dark"></div>
                </div>
                @if(auth()->check())
                    @include('courses.player.layouts.rightbar_loggedin')
                @else
                    @include('courses.player.layouts.rightbar_guest')
                @endif
            </div>
        </div>
    </div>
</div>