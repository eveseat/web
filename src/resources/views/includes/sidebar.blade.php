<aside class="main-sidebar elevation-4 sidebar-dark-primary">

  <!-- Logo -->
  <a href="{{ route('home') }}" class="brand-link">
    <img class="brand-image img-circle elevation-3" src="{{ asset('web/img/logo.png') }}" alt="SeAT" />
    <span class="brand-text font-weight-light">WHBOO</span>
  </a>

  <!-- sidebar: style can be found in sidebar.less -->
  <div class="sidebar">

    <!-- Sidebar user panel -->
    @if($user->name != 'admin')
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        {!! img('characters', 'portrait', $user->main_character_id, 64, ['class' => 'img-circle elevation-2', 'alt' => 'User Image'], false) !!}
      </div>
      <div class="info">
        <a href="{{ route('character.view.sheet', ['character' => $user->main_character]) }}" class="d-block">
          {{ $user->name }}
        </a>
      </div>
    </div>
    @endif

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu">

        @foreach($menu as $entry)

          {{-- determine if we should pop a treeview --}}
          @if(isset($entry['entries']))

            <li class="nav-item has-treeview">
              <a href="#" class="nav-link {{ Request::segment(1) === $entry['route_segment'] ? 'active' : null }}">
                <i class="nav-icon {{ $entry['icon'] }}"></i>

                @if (array_key_exists('label', $entry))

                  @if(array_key_exists('plural', $entry))
                    <p>
                      {{ trans_choice($entry['label'], $entry['plural'] ? 0 : 1) }}
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  @else
                    <p>
                      {{ trans($entry['label']) }}
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  @endif

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
                    @canany(is_array($item['permission']) ? $item['permission'] : [$item['permission']])

                      <li class="nav-item">
                        <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}" class="nav-link {{ isset($item['route']) ? (Request::url() === route($item['route']) ? 'active' : null) : null }}">

                          @if (array_key_exists('label', $item))

                            <i class="nav-icon {{ $item['icon'] ?? 'fa-circle-o' }}"></i>
                            @if(array_key_exists('plural', $item))
                              <p>{{ trans_choice($item['label'], $item['plural'] ? 0 : 1) }}</p>
                            @else
                              <p>{{ trans($item['label']) }}</p>
                            @endif

                          @else

                            <i class="nav-icon {{ $item['icon'] ?? 'fa-circle-o' }}"></i> {{ $item['name'] }}

                          @endif

                        </a>

                        @if(array_key_exists('entries', $item))
                          <ul class="nav nav-treeview">
                            @foreach($item['entries'] as $subitem)
                              <li class="nav-item">
                                <a href="{{ isset($subitem['route']) ? route($subitem['route']) : '#' }}" class="nav-link {{ isset($subitem['route']) ? (Request::url() === route($subitem['route']) ? 'active' : null) : null }}">
                                  @if (array_key_exists('label', $subitem))
                                    <i class="nav-icon {{ $subitem['icon'] ?? 'fa-circle-o' }}"></i>
                                    @if(array_key_exists('plural', $subitem))
                                      <p>{{ trans_choice($subitem['label'], $subitem['plural'] ? 0 : 1) }}</p>
                                    @else
                                      <p>{{ trans($subitem['label']) }}</p>
                                    @endif
                                  @else
                                    <i class="nav-icon {{ $subitem['icon'] ?? 'fa-circle-o' }}"></i>
                                    <p>{{ $subitem['name'] }}</p>
                                  @endif
                                </a>
                              </li>
                            @endforeach
                          </ul>
                        @endif

                      </li>

                    @endcanany

                    {{-- TODO: Get rid of this copy pasta by using a partial or something. --}}
                  @else

                    <li class="nav-item">
                      <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}" class="nav-link {{ isset($item['route']) ? (Request::url() === route($item['route']) ? 'active' : null) : null }}">
                        @if (array_key_exists('label', $item))

                          <i class="nav-icon {{ $item['icon'] ?? 'fa-circle-o' }}"></i>

                          @if(array_key_exists('plural', $item))
                            <p>{{ trans_choice($item['label'], $item['plural'] ? 0 : 1) }}</p>
                          @else
                            <p>{{ trans($item['label']) }}</p>
                          @endif

                        @else

                          <i class="nav-icon {{ $item['icon'] ?? 'fa-circle-o' }}"></i>
                          <p>{{ $item['name'] }}</p>

                        @endif

                      </a>

                      @if(array_key_exists('entries', $item))
                        <ul class="nav nav-treeview">
                          @foreach($item['entries'] as $subitem)
                            <li class="nav-item">
                              <a href="{{ isset($subitem['route']) ? route($subitem['route']) : '#' }}" class="nav-link {{ isset($subitem['route']) ? (Request::url() === route($subitem['route']) ? 'active' : null) : null }}">
                                @if (array_key_exists('label', $subitem))
                                  <i class="nav-icon {{ $subitem['icon'] ?? 'fa-circle-o' }}"></i>
                                  @if(array_key_exists('plural', $subitem))
                                    <p>{{ trans_choice($subitem['label'], $subitem['plural'] ? 0 : 1) }}</p>
                                  @else
                                    <p>{{ trans($subitem['label']) }}</p>
                                  @endif
                                @else
                                  <i class="nav-icon {{ $subitem['icon'] ?? 'fa-circle-o' }}"></i>
                                  <p>{{ $subitem['name'] }}</p>
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

            {{-- render custom menu links --}}
          @elseif($entry['route_segment'] === 'custom')

          <li class="nav-item">
              <a href="{{ $entry['route'] }}" class="nav-link" @if ($entry['new_tab'] == true) target="_blank" @endif>
                <i class="nav-icon {{ ($entry['icon'] != '') ? $entry['icon'] : 'fas fa-link' }}"></i>
                <p>{{ $entry['name'] }}</p>
              </a>
            </li>

            {{-- no entries, so this looks like a single menu --}}
          @else

            <li class="nav-item">
              <a href="{{ isset($entry['route']) ? route($entry['route']) : '#' }}" class="nav-link {{ Request::segment(1) === $entry['route_segment'] ? 'active' : null }}">

                @if (array_key_exists('label', $entry))
                  <i class="nav-icon {{ $entry['icon'] }}"></i>
                  @if(array_key_exists('plural', $entry))
                    <p>{{ trans_choice($entry['label'], $entry['plural'] ? 0 : 1) }}</p>
                  @else
                    <p>{{ trans($entry['label']) }}</p>
                  @endif
                @else
                  <i class="nav-icon {{ $entry['icon'] }}"></i>
                  <p>{{ $entry['name'] }}</p>
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
