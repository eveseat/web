<div class="pb-3">

  <ul class="nav nav-pills">

    @foreach($menu as $menu_entry)

      <li role="presentation" class="nav-item">

        <a href="{{ route($menu_entry['route'], $alliance) }}" class="nav-link @if ($viewname == $menu_entry['highlight_view']) active @endif">
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
