<aside class="main-sidebar">

  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="http://image.eveonline.com/Character/1_128.jpg" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>{{ $user->name }}</p>
        <!-- Status -->
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>

    <!-- search form -->
    <form action="#" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
            </button>
          </span>
      </div>
    </form>
    <!-- /.search form -->

    <!-- Sidebar Menu -->
    <ul class="sidebar-menu">
      <li class="header">Main Menu</li>

      @foreach($menu as $entry)

        {{-- determine if we should pop a treeview --}}
        @if(isset($entry['entries']))

          <li class="treeview {{ Request::segment(1) === $entry['route_segment'] ? 'active' : null }}">
            <a href="#">
              <i class="fa {{ $entry['icon'] }}"></i>
              <span>{{ $entry['name'] }}</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">

              @foreach($entry['entries'] as $item)

                <li class="{{ isset($item['route']) ? (Request::url() === $item['route'] ? 'active' : null) : null }}">
                  <a href="{{ $item['route'] or '#' }}">{{ $item['name'] }}</a>
                </li>

              @endforeach
            </ul>
          </li>

          {{-- no entries, so this looks like a single menu --}}
        @else

          <li class="{{ Request::segment(1) === $entry['route_segment'] ? 'active' : null }}">
            <a href="{{ $entry['route'] or '#' }}">
              <i class="fa {{ $entry['icon'] }}"></i> <span>{{ $entry['name'] }}</span>
            </a>
          </li>

        @endif

      @endforeach
    </ul>
    <!-- /.sidebar-menu -->

  </section>
  <!-- /.sidebar -->
</aside>
