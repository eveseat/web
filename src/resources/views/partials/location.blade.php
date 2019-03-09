<a href="//evemaps.dotlan.net/system/{{ $location->solar_system->itemName }}" target="_blank">
  <span class="fa fa-map-marker"></span>
</a>
@if(!is_null($location->structure))
  {{ $location->structure->name }}
@elseif(!is_null($location->station))
  {{ $location->station->stationName }}
@else
  {{ $location->solar_system->itemName }}
@endif