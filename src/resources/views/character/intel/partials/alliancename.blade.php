@if($row->allianceID !== 0)
  {!! img('alliance', $row->allianceID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
  {{ $row->allianceName }}
@endif
