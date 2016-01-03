<div style="padding-bottom: 15px;">

  <ul class="nav nav-pills">

    @foreach($menu as $menu_entry)

      @if(auth()->user()->has($menu_entry['permission']))

        <li role="presentation"
            class="@if ($viewname == $menu_entry['highlight_view']) active @endif">

          <a href="{{ route($menu_entry['route'], $sheet->corporationID) }}">
            {{ $menu_entry['name'] }}
          </a>

        </li>

      @endif

    @endforeach

  </ul>

</div>
