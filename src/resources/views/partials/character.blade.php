@if ($character->name && $character->name !== trans('web::seat.unknown'))
  <a href="{{ route('character.view.default', ['character_id' => $character->character_id ?? $character->entity_id]) }}">
    {!! img('characters', 'portrait', $character->character_id ?? $character->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
    {{ $character->name }}
  </a>
@else
  {!! img('characters', 'portrait', $character->character_id ?? $character->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
  {!!
    cache(sprintf('name_id:%s', $character->character_id ?? $character->entity_id), function () use ($character) {
      return sprintf('<span class="id-to-name" data-id="%d">%s</span>', $character->character_id ?? $character->entity_id, trans('web::seat.unknown'));
    })
  !!}
@endif
