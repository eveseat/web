@if($row->shipType != 'Unknown Type')
  <i class="" data-toggle="tooltip"
     title="" data-original-title="{{ $row->shipType }}">
    {!! img('type', $row->shipTypeID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
  </i>
@endif
{{ $row->shipTypeName }}
