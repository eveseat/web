@if (request('all_linked_characters') === "true")
  {!! img('character', $character_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
@endif

@if(! is_null($alliance))
  {!! img('alliance', $alliance->alliance_id ?? $alliance->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
  @if (! is_null(cache('name_id:' . $alliance->alliance_id ?? $alliance->entity_id)))
    {{cache('name_id:' . $alliance->alliance_id ?? $alliance->entity_id)}}
  @else
    <span class="id-to-name" data-id="{{ $alliance->alliance_id ?? $alliance->entity_id }}">{{ trans('web::seat.unknown') }}</span>
  @endif
@endif
