@if($row->first_party_id != 0)
  {!! img('auto', $row->first_party_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
  <span rel="id-to-name">{{ $row->first_party_id }}</span>
@else
  n/a
@endif
