@if(! is_null($row->corporation_id))
{!! img('corporation', $row->corporation_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
<span rel="id-to-name">{{ $row->corporation_id }}</span>
@endif