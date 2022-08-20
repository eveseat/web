<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- logo -->
        <h1 class="navbar-brand navbar-brand-autodark seat-font">
            <a href="{{ url('/') }}">
                <img src="{{ asset('web/img/logo.png') }}" width="110px" height="110px" alt="{{ config('app.name') }}" />
            </a>
        </h1>
        <!-- ./logo -->
        <!-- sidebar-menu-entries -->
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
                @foreach($menu as $entry)
                    @include('web::includes.menu.sidebar.level0', ['item' => $entry, 'is_custom_link' => $entry['route_segment'] === 'custom'])
                @endforeach
            </ul>
        </div>
        <!-- ./sidebar-menu-entries -->
    </div>
</aside>
