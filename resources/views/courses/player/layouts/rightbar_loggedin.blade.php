@php
    $findToken = \DB::table('user_access_tokens')->where('user_id', auth()->user()->id)->first();
@endphp
<div class="list-group m-2 ">
    <a href="{{ env('URL_PANEL') }}/?auth_token={{ $findToken->token }}" class="list-group-item list-group-item-action border-0 ">
        <i class="icofont-graduate-alt fs-6 me-3"></i>User Dashboard
    </a>
    <div><hr class="dropdown-divider border-dark"></div>
    <a href="" class="list-group-item list-group-item-action border-0 ">
        <i class="icofont-logout fs-6 me-3"></i>Signout
    </a>
</div>