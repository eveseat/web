@if($row->ownerID1 != 0)
  {!! img('auto', $row->ownerID1, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
  {{ $row->ownerName1 }}
@else
  n/a
@endif
