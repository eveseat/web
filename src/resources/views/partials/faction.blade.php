{!! img('faction', $faction->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
@if (! is_null(cache('name_id:' . $faction->entity_id)))
  {{ cache('name_id:' . $faction->entity_id) }}
@else
  <span class="id-to-name" data-id="{{ $faction->entity_id }}">{{ trans('web::seat.unknown') }}</span>
@endif

