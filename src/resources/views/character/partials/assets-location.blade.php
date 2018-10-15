<b>
  @if($row->locationName == '')
    Unknown Structure ({{ $row->first()->location_id }})
  @else
    {{ $row->locationName }}
  @endif
</b>
<span class="pull-right">
  <i>{{ $number_items }}
    {{ trans('web::seat.items_taking') }}
    {{number_metric($volume)}} m&sup3;
  </i>
</span>

<table class="table compact table-hover table-condensed table-responsive location-table" data-location-id="{{$row->location_id}}">
  <thead>
  <tr>
    <th></th>
    <th>Quantity</th>
    <th>Type</th>
    <th>Volume</th>
    <th>Group</th>
  </tr>
  </thead>
</table>

