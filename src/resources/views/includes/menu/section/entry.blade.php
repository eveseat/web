@if(array_key_exists('entries', $data))
    @if(! $is_root)
    <div class="dropend">
    @endif
    <a href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" @class([
        'nav-link' => $is_root,
        'dropdown-item' => ! $is_root,
        'dropdown-toggle',
        'active' => collect($data['entries'])->filter(function ($item) { return array_key_exists('route', $item) && request()->route()->getName() == $item['route']; })->isNotEmpty(),
    ])>
        @includeWhen(array_key_exists('icon', $data), 'web::includes.menu.section.icon')
        @include('web::includes.menu.section.title')
    </a>
    <div class="dropdown-menu">
        @foreach($data['entries'] as $entry)
            @include('web::includes.menu.section.entry', [
                'data' => $entry,
                'is_root' => false,
                'url' => array_key_exists('route', $entry) ? route($entry['route'], $entity_id) : '#',
            ])
        @endforeach
    </div>
    @if(! $is_root)
    </div>
    @endif
@else
    <a href="{{ route($data['route'], $entity_id) }}" @class([
        'nav-link' => $is_root,
        'dropdown-item' => ! $is_root,
        'active' => request()->route()->getName() == $data['route'],
        'dropdown-toggle' => array_key_exists('entries', $data),
    ])>
        @includeWhen(array_key_exists('icon', $data), 'web::includes.menu.section.icon')
        @include('web::includes.menu.section.title')
    </a>
@endif