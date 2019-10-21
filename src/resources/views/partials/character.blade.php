@if (request('all_linked_characters') === "true")
  {!! img('character', $character_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
@endif

@if ($character->name && $character->name !== trans('web::seat.unknown'))

  <a href="{{ route('character.view.default', ['character_id' => $character->character_id ?? $character->entity_id]) }}">
    {!! img('character', $character->character_id ?? $character->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
    {{ $character->name }}
  </a>

@else

  {!! img('character', $character->character_id ?? $character->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
  @if (! is_null(cache('name_id:' . $character->character_id ?? $character->entity_id)))
    {{cache('name_id:' . $character->character_id ?? $character->entity_id)}}
  @else
    <span class="id-to-name" data-id="{{ $character->character_id ?? $character->entity_id }}">{{ trans('web::seat.unknown') }}</span>
  @endif

@endif
