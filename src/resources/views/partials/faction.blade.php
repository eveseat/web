{!! img('faction', $faction, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
@if (! is_null(cache('name_id:' . $faction)))
  {{ cache('name_id:' . $faction) }}
@else
  <span class="id-to-name" data-id="{{ $faction }}">{{ trans('web::seat.unknown') }}</span>
@endif

