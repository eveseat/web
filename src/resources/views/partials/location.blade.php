@if(is_null($location))
  {{ trans('web::seat.unknown') }}
@else
  @if(! is_null($location->solar_system))
  <a href="//evemaps.dotlan.net/system/{{ $location->solar_system->itemName }}" target="_blank">
    <span class="fas fa-map-marker-alt"></span>
  </a>
  @endif
  @if(!is_null($location->structure))
    {{ $location->structure->name }}
  @elseif(!is_null($location->station))
    {{ $location->station->stationName }}
  @else
    @if(is_null($location->solar_system))
      {{ trans('web::seat.unknown') }}
    @else
      {{ $location->solar_system->itemName }}
    @endif
  @endif
@endif