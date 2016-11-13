@if($row->allianceID != null)
  {!! img('alliance', $row->allianceID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
  {{ $row->alliance }}
@endif
