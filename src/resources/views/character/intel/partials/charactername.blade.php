{!! img('character', $row->character_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
<span class="id-to-name" data-id="{{ $row->character_id }}">{{ trans('web::seat.unknown') }}</span>
