{!! img('character', $row->character_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
<span class="id-to-name" data-id="{{ $row->character_id }}">{{ trans('web::seat.unknown') }}</span>
@if($row->ownerID == $row->victimID)
  <span class="text-red"><i>(loss!)</i></span>
@endif
