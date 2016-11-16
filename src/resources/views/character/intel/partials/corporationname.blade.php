@if($row->corporationID !== 0)
  {!! img('corporation', $row->corporationID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
  {{ $row->corporationName }}
@endif
