<a href="{{ route('character.view.sheet', ['character_id' => $row->ceo_id]) }}">
  {!! img('character', $row->ceo_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
  <span class="id-to-name" data-id="{{ $row->ceo_id }}">{{ trans('web::seat.unknown') }}</span>
</a>
