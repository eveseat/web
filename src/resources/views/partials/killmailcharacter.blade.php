{!! img('character', $row->characterID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
{{ $row->characterName }}
@if($row->ownerID == $row->victimID)
  <span class="text-red"><i>(loss!)</i></span>
@endif
