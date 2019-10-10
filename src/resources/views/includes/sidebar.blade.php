<aside class="main-sidebar sidebar-dark-primary">

  <!-- Logo -->
  <a href="{{ route('home') }}" class="brand-link">
    <span class="brand-text font-weight-light"><b>S</b>T</span>
    <span class="brand-text font-weight-light">S<b>e</b>AT</span>
  </a>

  <!-- sidebar: style can be found in sidebar.less -->
  <div class="sidebar">

    <!-- Sidebar user panel -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="//image.eveonline.com/Character/{{ $user->id }}_128.jpg" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        @if(auth()->user()->name == 'admin')
        <span>{{ $user->name }}</span>
        @else
        <a href="{{ route('character.view.sheet', ['character_id' => $user->character_id]) }}" class="d-block">
          {{ $user->name }}
        </a>
        @endif
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        <li class="nav-header">{{ trans('web::seat.main_menu') }}</li>

        @foreach($menu as $entry)

          {{-- determine if we should pop a treeview --}}
          @if(isset($entry['entries']))

            <li class="nav-item has-treeview {{ Request::segment(1) === $entry['route_segment'] ? 'active' : null }}">
              <a href="#" class="nav-link">
                <i class="nav-icon fas {{ $entry['icon'] }}"></i>

                @if (array_key_exists('label', $entry))

                  <p>
                    @if(array_key_exists('plural', $entry))
                      {{ trans_choice($entry['label'], 2) }}
                    @else
                      {{ trans($entry['label']) }}
                    @endif
                    <i class="right fas fa-angle-left"></i>
                  </p>

                @else

                  <p>
                    {{ $entry['name'] }}
                    <i class="right fas fa-angle-left"></i>
                  </p>

                @endif

              </a>
              <ul class="nav nav-treeview">

                @foreach($entry['entries'] as $item)

                  {{-- check if a permisison is required an if its given --}}
                  @if(array_key_exists('permission', $item))

                    {{-- permision is required. check it --}}
                    @if(auth()->user()->has($item['permission'], false))

                      <li class="nav-item {{ isset($item['route']) ? (Request::url() === route($item['route']) ? 'active' : null) : null }}">
                        <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}" class="nav-link">

                          @if (array_key_exists('label', $item))

                            <i class="nav-icon fas {{ $item['icon'] ?? 'fa-circle-o' }}"></i>
                            @if(array_key_exists('plural', $item))
                              {{ trans_choice($item['label'], 2) }}
                            @else
                              {{ trans($item['label']) }}
                            @endif

                          @else

                            <i class="nav-icon fas {{ $item['icon'] ?? 'fa-circle-o' }}"></i> {{ $item['name'] }}

                          @endif

                        </a>

                        @if(array_key_exists('entries', $item))
                          <ul class="nav nav-treeview">
                            @foreach($item['entries'] as $subitem)
                              <li class="nav-item {{ isset($subitem['route']) ? (Request::url() === route($subitem['route']) ? 'active' : null) : null }}">
                                <a href="{{ isset($subitem['route']) ? route($subitem['route']) : '#' }}" class="nav-link">
                                  @if (array_key_exists('label', $subitem))
                                    <i class="nav-icon fas {{ $subitem['icon'] ?? 'fa-circle-o' }}"></i>
                                    @if(array_key_exists('plural', $subitem))
                                      {{ trans_choice($subitem['label'], 2) }}
                                    @else
                                      {{ trans($subitem['label']) }}
                                    @endif
                                  @else
                                    <i class="nav-icon fas {{ $subitem['icon'] ?? 'fa-circle-o' }}"></i> {{ $subitem['name'] }}
                                  @endif
                                </a>
                              </li>
                            @endforeach
                          </ul>
                        @endif

                      </li>

                    @endif

                    {{-- TODO: Get rid of this copy pasta by using a partial or something. --}}
                  @else

                    <li class="nav-item {{ isset($item['route']) ? (Request::url() === route($item['route']) ? 'active' : null) : null }}">
                      <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}" class="nav-link">
                        @if (array_key_exists('label', $item))

                          <i class="nav-icon fas {{ $item['icon'] ?? 'fa-circle-o' }}"></i>

                          @if(array_key_exists('plural', $item))
                            {{ trans_choice($item['label'], 2) }}
                          @else
                            {{ trans($item['label']) }}
                          @endif

                        @else

                          <i class="nav-icon fas {{ $item['icon'] ?? 'fa-circle-o' }}"></i> {{ $item['name'] }}

                        @endif

                      </a>

                      @if(array_key_exists('entries', $item))
                        <ul class="nav nav-treeview">
                          @foreach($item['entries'] as $subitem)
                            <li class="nav-item {{ isset($subitem['route']) ? (Request::url() === route($subitem['route']) ? 'active' : null) : null }}">
                              <a href="{{ isset($subitem['route']) ? route($subitem['route']) : '#' }}" class="nav-link">
                                @if (array_key_exists('label', $subitem))
                                  <i class="nav-icon fas {{ $subitem['icon'] ?? 'fa-circle-o' }}"></i>
                                  @if(array_key_exists('plural', $subitem))
                                    {{ trans_choice($subitem['label'], 2) }}
                                  @else
                                    {{ trans($subitem['label']) }}
                                  @endif
                                @else
                                  <i class="nav-icon fas {{ $subitem['icon'] ?? 'fa-circle-o' }}"></i> {{ $subitem['name'] }}
                                @endif
                              </a>
                            </li>
                          @endforeach
                        </ul>
                      @endif

                    </li>

                  @endif

                @endforeach
              </ul>
            </li>

            {{-- no entries, so this looks like a single menu --}}
          @else

            <li class="nav-item {{ Request::segment(1) === $entry['route_segment'] ? 'active' : null }}">
              <a href="{{ isset($entry['route']) ? route($entry['route']) : '#' }}" class="nav-link">

                @if (array_key_exists('label', $entry))
                  <i class="nav-icon fas {{ $entry['icon'] }}"></i> <span>{{ trans($entry['label']) }}</span>
                @else
                  <i class="nav-icon fas {{ $entry['icon'] }}"></i> <span>{{ $entry['name'] }}</span>
                @endif

              </a>
            </li>

          @endif

        @endforeach

      </ul>
    </nav>
    <!-- /.sidebar-menu -->

  </div>
  <!-- /.sidebar -->
</aside>
