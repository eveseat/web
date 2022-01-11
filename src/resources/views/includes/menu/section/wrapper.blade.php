<ul class="navbar-nav">
    @foreach($section_menu ?? [] as $entry)
        @if(array_key_exists('entries', $entry))
            <li @class([
            'nav-item',
            'dropdown',
            'active' => collect($entry['entries'])->filter(function ($item) { return array_key_exists('route', $item) && request()->route()->getName() == $item['route'] || array_key_exists('entries', $item) && collect($item['entries'])->filter(function ($item) { return request()->route()->getName() == $item['route']; })->isNotEmpty(); })->isNotEmpty()
            ]) class="nav-item dropdown">
                @include('web::includes.menu.section.entry', [
                    'data' => $entry,
                    'is_root' => true,
                ])
            </li>
        @else
            <li class="nav-item">
                @include('web::includes.menu.section.entry', [
                    'data' => $entry,
                    'is_root' => true,
                ])
            </li>
        @endif
    @endforeach
</ul>