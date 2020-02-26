@if (! is_null($row->location))
  {{ $row->location->name }}
@else
  Unknown Location
@endif