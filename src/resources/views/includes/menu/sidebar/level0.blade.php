@if(array_key_exists('route', $item))
    <li class="nav-item">
        @if(request()->route()->getName() == $item['route'])
            <a href="{{ $is_custom_link ? $item['route'] : route($item['route']) }}" class="nav-link active" @if(array_key_exists('new_tab', $item) && $item['new_tab'])target="_blank"@endif>
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class="{{ $item['icon'] }}"></i>
                </span>
                @if(array_key_exists('label', $item))
                    @if(array_key_exists('plural', $item))
                        <span class="nav-link-title">{{ ucfirst(trans_choice($item['label'], $item['plural'])) }}</span>
                    @else
                        <span class="nav-link-title">{{ ucfirst(trans($item['label'])) }}</span>
                    @endif
                @else
                    <span class="nav-link-title">{{ ucfirst($item['name']) }}</span>
                @endif
            </a>
        @else
            <a href="{{ $is_custom_link ? $item['route'] : route($item['route']) }}" class="nav-link" @if(array_key_exists('new_tab', $item) && $item['new_tab'])target="_blank"@endif>
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class="{{ $item['icon'] }}"></i>
                </span>
                @if(array_key_exists('label', $item))
                    @if(array_key_exists('plural', $item))
                        <span class="nav-link-title">{{ ucfirst(trans_choice($item['label'], $item['plural'])) }}</span>
                    @else
                        <span class="nav-link-title">{{ ucfirst(trans($item['label'])) }}</span>
                    @endif
                @else
                    <span class="nav-link-title">{{ ucfirst($item['name']) }}</span>
                @endif
            </a>
        @endif
    </li>
@else
    @if(request()->segment(1) == $item['route_segment'])
        <li class="nav-item dropdown active">
            <a class="nav-link dropdown-toggle" href="#navbar-{{ $item['name'] }}" data-bs-toggle="dropdown" data-bs-auto-close="true" role="button" aria-expanded="false">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class="{{ $item['icon'] }}"></i>
                </span>
                @if(array_key_exists('label', $item))
                    @if(array_key_exists('plural', $item))
                        <span class="nav-link-title">{{ ucfirst(trans_choice($item['label'], $item['plural'])) }}</span>
                    @else
                        <span class="nav-link-title">{{ ucfirst(trans($item['label'])) }}</span>
                    @endif
                @else
                    <span class="nav-link-title">{{ ucfirst($item['name']) }}</span>
                @endif
            </a>
            <div class="dropdown-menu show">
                @foreach($item['entries'] as $entry)
                    @include('web::includes.menu.sidebar.level1', ['item' => $entry])
                @endforeach
            </div>
        </li>
    @else
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#navbar-{{ $item['name'] }}" data-bs-toggle="dropdown" data-bs-auto-close="true" role="button" aria-expanded="false">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class="{{ $item['icon'] }}"></i>
                </span>
                @if(array_key_exists('label', $item))
                    @if(array_key_exists('plural', $item))
                        <span class="nav-link-title">{{ ucfirst(trans_choice($item['label'], $item['plural'])) }}</span>
                    @else
                        <span class="nav-link-title">{{ ucfirst(trans($item['label'])) }}</span>
                    @endif
                @else
                    <span class="nav-link-title">{{ ucfirst($item['name']) }}</span>
                @endif
            </a>
            <div class="dropdown-menu">
                @foreach($item['entries'] as $entry)
                    @include('web::includes.menu.sidebar.level1', ['item' => $entry])
                @endforeach
            </div>
        </li>
    @endif
@endif