{!! img('character', $row->character_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
<span rel="id-to-name">{{ $row->character_id }}</span>
@if($row->ownerID == $row->victimID)
  <span class="text-red"><i>(loss!)</i></span>
@endif
