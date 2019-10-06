<a href="//evemaps.dotlan.net/system/{{ $system }}" target="_blank">
  <i class="fa fa-map-marker"></i>
</a>

{{ $system }}

<span class="
  @if ($security >= 0.5)
    text-green
@elseif ($security < 0.5 && $security > 0.0)
    text-warning
@else
    text-red
@endif">
  <i>({{ round($security, 2) }})</i>
</span>
