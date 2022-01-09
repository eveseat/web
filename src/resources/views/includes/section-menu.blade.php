<ul class="navbar-nav">
    @foreach($section_menu ?? [] as $entry)
        @if(array_key_exists('entries', $entry))
            <li class="nav-item dropdown @if(collect($entry['entries'])->filter(function ($item) { return request()->route()->getName() == $item['route']; })->isNotEmpty()) active @endif">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                    @if(array_key_exists('icon', $entry))
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="{{ $entry['icon'] }}"></i>
                        </span>
                    @endif
                    @if(array_key_exists('plural', $entry))
                        <span class="nav-link-title">{{ trans_choice($entry['label'], $entry['plural'] ? 0 : 1) }}</span>
                    @else
                        <span class="nav-link-title">{{ trans($entry['label']) }}</span>
                    @endif
                </a>
                <div class="dropdown-menu">
                    @foreach($entry['entries'] as $sub_entry)
                        <a href="{{ route($sub_entry['route'], $character) }}" class="dropdown-item @if(request()->route()->getName() == $sub_entry['route']) active @endif">
                            @if(array_key_exists('icon', $sub_entry))
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="{{ $sub_entry['icon'] }}"></i>
                                </span>
                            @endif
                            @if(array_key_exists('plural', $sub_entry))
                                <span class="nav-link-title">{{ trans_choice($sub_entry['label'], $sub_entry['plural'] ? 0 : 1) }}</span>
                            @else
                                <span class="nav-link-title">{{ trans($sub_entry['label']) }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </li>
        @else
            <li class="nav-item @if(request()->route()->getName() == $entry['route']) active @endif">
                <a href="{{ route($entry['route'], $character) }}" class="nav-link">
                    @if(array_key_exists('icon', $entry))
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="{{ $entry['icon'] }}"></i>
                        </span>
                    @endif
                    @if(array_key_exists('plural', $entry))
                        <span class="nav-link-title">{{ trans_choice($entry['label'], $entry['plural'] ? 0 : 1) }}</span>
                    @else
                        <span class="nav-link-title">{{ trans($entry['label']) }}</span>
                    @endif
                </a>
            </li>
        @endif
    @endforeach
</ul>