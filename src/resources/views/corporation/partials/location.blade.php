@if (! is_null($row->location))
  {{ $row->location->name }}
@else
  Unknown Location
@endif

@if (! is_null($row->ship_type_id))
  <i class="pull-right" data-toggle="tooltip" title="" data-original-title="{{ optional($row->type)->typeName }}">
    {!! img('type', $row->ship_type_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
  </i>
@endif