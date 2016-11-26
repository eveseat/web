@if($row->allianceID)
  {!! img('alliance', $row->allianceID, 64, ['class' => 'img-circle eve-icon medium-icon']) !!}
  {{ $row->allianceName }}
@endif
