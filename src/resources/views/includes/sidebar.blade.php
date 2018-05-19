<aside class="main-sidebar">

  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="//image.eveonline.com/Character/{{ $user->id }}_128.jpg"
             class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>
          @if(auth()->user()->name == 'admin')
          <span>{{ trans('web::seat.hello') }}, {{ $user->name }}</span>
          @else
          <a href="{{ route('character.view.sheet', ['character_id' => $user->character_id]) }}">
            {{ trans('web::seat.hello') }}, {{ $user->name }}
          </a>
          @endif
        </p>
        <!-- Status -->
        <a href="#"><i class="fa fa-circle text-success"></i> {{ trans('web::seat.online') }}</a>
      </div>
    </div>

    <!-- search form -->
    <form action="{{ route('support.search') }}" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="{{ trans('web::seat.search') }}...">
        <span class="input-group-btn">
            <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
            </button>
          </span>
      </div>
    </form>
    <!-- /.search form -->

    <!-- Sidebar Menu -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">{{ trans('web::seat.main_menu') }}</li>

      @foreach($menu as $entry)

        {{-- determine if we should pop a treeview --}}
        @if(isset($entry['entries']))

          <li class="treeview {{ Request::segment(1) === $entry['route_segment'] ? 'active' : null }}">
            <a href="#">
              <i class="fa {{ $entry['icon'] }}"></i>

              @if (array_key_exists('label', $entry))

                <span>
                  @if(array_key_exists('plural', $entry))
                    {{ trans_choice($entry['label'], 2) }}
                  @else
                    {{ trans($entry['label']) }}
                  @endif
                </span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>

              @else

                <span>{{ $entry['name'] }}</span> <i class="fa fa-angle-left pull-right"></i>

              @endif

            </a>
            <ul class="treeview-menu">

              @foreach($entry['entries'] as $item)

                {{-- check if a permisison is required an if its given --}}
                @if(array_key_exists('permission', $item))

                  {{-- permision is required. check it --}}
                  @if(auth()->user()->has($item['permission'], false))

                    <li class="{{ isset($item['route']) ? (Request::url() === route($item['route']) ? 'active' : null) : null }}">
                      <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}">

                        @if (array_key_exists('label', $item))

                          <i class="fa {{ $item['icon'] or 'fa-circle-o' }}"></i>
                          @if(array_key_exists('plural', $item))
                            {{ trans_choice($item['label'], 2) }}
                          @else
                            {{ trans($item['label']) }}
                          @endif

                        @else

                          <i class="fa {{ $item['icon'] or 'fa-circle-o' }}"></i> {{ $item['name'] }}

                        @endif

                        @if(array_key_exists('entries', $item))
                          <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                        @endif

                      </a>

                      @if(array_key_exists('entries', $item))
                        <ul class="treeview-menu">
                          @foreach($item['entries'] as $subitem)
                            <li class="{{ isset($subitem['route']) ? (Request::url() === route($subitem['route']) ? 'active' : null) : null }}">
                              <a href="{{ isset($subitem['route']) ? route($subitem['route']) : '#' }}">
                                @if (array_key_exists('label', $subitem))
                                  <i class="fa {{ $subitem['icon'] or 'fa-circle-o' }}"></i>
                                  @if(array_key_exists('plural', $subitem))
                                    {{ trans_choice($subitem['label'], 2) }}
                                  @else
                                    {{ trans($subitem['label']) }}
                                  @endif
                                @else
                                  <i class="fa {{ $subitem['icon'] or 'fa-circle-o' }}"></i> {{ $subitem['name'] }}
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

                  <li class="{{ isset($item['route']) ? (Request::url() === route($item['route']) ? 'active' : null) : null }}">
                    <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}">
                      @if (array_key_exists('label', $item))

                        <i class="fa {{ $item['icon'] or 'fa-circle-o' }}"></i>

                        @if(array_key_exists('plural', $item))
                          {{ trans_choice($item['label'], 2) }}
                        @else
                          {{ trans($item['label']) }}
                        @endif

                      @else

                        <i class="fa {{ $item['icon'] or 'fa-circle-o' }}"></i> {{ $item['name'] }}

                      @endif

                      @if(array_key_exists('entries', $item))
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                      </span>
                      @endif

                    </a>

                    @if(array_key_exists('entries', $item))
                      <ul class="treeview-menu">
                        @foreach($item['entries'] as $subitem)
                          <li class="{{ isset($subitem['route']) ? (Request::url() === route($subitem['route']) ? 'active' : null) : null }}">
                            <a href="{{ isset($subitem['route']) ? route($subitem['route']) : '#' }}">
                              @if (array_key_exists('label', $subitem))
                                <i class="fa {{ $subitem['icon'] or 'fa-circle-o' }}"></i>
                                @if(array_key_exists('plural', $subitem))
                                  {{ trans_choice($subitem['label'], 2) }}
                                @else
                                  {{ trans($subitem['label']) }}
                                @endif
                              @else
                                <i class="fa {{ $subitem['icon'] or 'fa-circle-o' }}"></i> {{ $subitem['name'] }}
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

          <li class="{{ Request::segment(1) === $entry['route_segment'] ? 'active' : null }}">
            <a href="{{ isset($entry['route']) ? route($entry['route']) : '#' }}">

              @if (array_key_exists('label', $entry))
                <i class="fa {{ $entry['icon'] }}"></i> <span>{{ trans($entry['label']) }}</span>
              @else
                <i class="fa {{ $entry['icon'] }}"></i> <span>{{ $entry['name'] }}</span>
              @endif

            </a>
          </li>

        @endif

      @endforeach

    </ul>
    <!-- /.sidebar-menu -->

  </section>
  <!-- /.sidebar -->
</aside>
