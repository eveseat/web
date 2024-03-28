<a href="{{ route('seatcore::character.view.sheet', ['character_id' => $row->ceo_id]) }}">
  {!! img('characters', 'portrait', $row->ceo_id, 64, ['class' => 'img-circle eve-icon medium-icon']) !!}
  <span class="id-to-name" data-id="{{ $row->ceo_id }}">{{ trans('web::seat.unknown') }}</span>
</a>
