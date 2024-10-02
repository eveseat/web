@if(is_null($building))
  {{ trans('web::seat.unknown') }}
@else
  @if(!is_null($building->structure))
    {{ $building->structure->name }}
  @elseif(!is_null($building->station))
    {{ $building->station->name }}
  @else
    {{ trans('web::seat.unknown') }}
  @endif
@endif