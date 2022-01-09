<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- logo -->
        <h1 class="navbar-brand navbar-brand-autodark">
            <a href=".">S<b>e</b>AT</a>
        </h1>
        <!-- ./logo -->
        <!-- sidebar-menu-entries -->
        <div class="collapse navbar-collapse" id="navbar-menu">
            <ul class="navbar-nav pt-lg-3">
                @foreach($menu as $entry)
                    @include('web::includes.menu.sidebar.level0', ['item' => $entry, 'is_custom_link' => $entry['route_segment'] === 'custom'])
                @endforeach
            </ul>
        </div>
        <!-- ./sidebar-menu-entries -->
    </div>
</aside>
