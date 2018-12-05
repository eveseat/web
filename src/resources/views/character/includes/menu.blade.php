<div style="padding-bottom: 15px;">

  <ul class="nav nav-pills">

    @foreach($menu as $menu_entry)

      <li role="presentation" class="@if ($viewname == $menu_entry['highlight_view']) active @endif">

        <a href="{{ route($menu_entry['route'], $summary->character_id) }}">
          @if (array_key_exists('label', $menu_entry))
            @if(array_key_exists('plural', $menu_entry))
              {{ trans_choice($menu_entry['label'], 2) }}
            @else
              {{ trans($menu_entry['label']) }}
            @endif
          @else
            {{ $menu_entry['name'] }}
          @endif
        </a>

      </li>

    @endforeach

  </ul>

</div>
