<aside class="main-sidebar">

  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="//image.eveonline.com/Character/{{ setting('main_character_id') }}_128.jpg"
             class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>
          @if(is_null(setting('main_character_name')))
            <a href="{{ route('profile.view') }}">{{ trans('web::seat.no_main_char') }}!</a>
          @else
            {{ trans('web::seat.hello') }}, {{ setting('main_character_name') }}
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
    <ul class="sidebar-menu">
      <li class="header">{{ trans('web::seat.main_menu') }}</li>

      @foreach($menu as $entry)

        {{-- determine if we should pop a treeview --}}
        @if(isset($entry['entries']))

          <li class="treeview {{ Request::segment(1) === $entry['route_segment'] ? 'active' : null }}">
            <a href="#">
              <i class="fa {{ $entry['icon'] }}"></i>
              @if (array_key_exists('label', $entry))
              <span>{{ trans($entry['label']) }}</span> <i class="fa fa-angle-left pull-right"></i>
              @else
              <span>{{ $entry['name'] }}</span> <i class="fa fa-angle-left pull-right"></i>
              @endif
            </a>
            <ul class="treeview-menu">

              @foreach($entry['entries'] as $item)

                <li class="{{ isset($item['route']) ? (Request::url() === route($item['route']) ? 'active' : null) : null }}">
                  <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}">
                    @if (array_key_exists('label', $item))
                    <i class="fa {{ $item['icon'] or 'fa-circle-o' }}"></i> {{ trans($item['label']) }}
                    @else
                    <i class="fa {{ $item['icon'] or 'fa-circle-o' }}"></i> {{ $item['name'] }}
                    @endif
                  </a>
                </li>

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
