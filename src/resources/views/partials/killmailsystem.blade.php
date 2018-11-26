{{ $place->itemName }}

<span class="
  @if ($place->security >= 0.5)
    text-green
@elseif ($place->security < 0.5 && $place->security > 0.0)
    text-warning
@else
    text-red
@endif">
  <i>({{ round($place->security, 2) }})</i>
</span>
