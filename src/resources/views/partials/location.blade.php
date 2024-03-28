@if(is_null($location))
  {{ trans('web::seat.unknown') }}
@else
  @if(! is_null($location->solar_system))
    @include('web::partials.system', ['system' => $location->solar_system->name, 'security' => $location->solar_system->security])
  @elseif(!is_null($location->structure))
    {{ $location->structure->name }}
  @elseif(!is_null($location->station))
    {{ $location->station->stationName }}
  @else
    {{ trans('web::seat.unknown') }}
  @endif
@endif