@if($row->ownerID2 != 0)
  {!! img('auto', $row->ownerID2, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
  {{ $row->ownerName2 }}
@else
  n/a
@endif
