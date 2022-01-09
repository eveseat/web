@if(array_key_exists('route', $item))
    @if(request()->route()->getName() == $item['route'])
        <a href="{{ route($item['route']) }}" class="dropdown-item active">
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
        <a href="{{ route($item['route']) }}" class="dropdown-item">
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
@else
    <div class="dropend">
        <a href="#navbar-{{ $item['name'] }}" class="dropdown-item dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="true" role="button" aria-expanded="false">
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
                @include('web::includes.menu.level1.blade.php', ['item' => $entry])
            @endforeach
        </div>
    </div>
@endif