<div class="sidebar px-4 py-4 py-md-4 me-0">
    <div class="d-flex flex-column h-100">

        @include('layouts.includes.gadgets.brand')

        @php
            $server = env('URL_ADMIN');
            $fav = $bases['siteFavicon'] ?? '';
            $fullFav = $server . "/" . $fav;
        @endphp

                <!-- Menu: main ul -->
        @include('layouts.includes.gadgets.menu')

        <!-- Logo at bottom -->
        <div class="sidebar-logo-container mt-auto d-flex justify-content-center align-items-center py-3">
            <a href="{{ env('URL_FRONT') }}" class="d-inline-block">
                <img src="{{ $fullFav }}" alt="Site Logo" class="sidebar-logo-img" />
            </a>
        </div>

        <!-- Theme: Switch Theme -->
        {{-- @include('layouts.includes.gadgets.switch-theme') --}}

    </div>
</div>

