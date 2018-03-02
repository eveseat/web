@if($row->alliance_id)
  {!! img('alliance', $row->alliance_id, 64, ['class' => 'img-circle eve-icon medium-icon']) !!}
   <span rel="id-to-name">{{ $row->alliance_id }}</span>
@endif
