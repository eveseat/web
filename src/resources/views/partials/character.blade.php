@if($character === null)
  {!! img('characters', 'portrait', null, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
  <span>{{ trans('web::seat.unknown') }}</span>
@elseif ($character->name && $character->name !== trans('web::seat.unknown'))
  @if(\Seat\Eveapi\Models\Character\CharacterInfo::find($character->character_id ?? $character->entity_id))
    <a href="{{ route('character.view.default', ['character' => $character->character_id ?? $character->entity_id]) }}">
      {!! img('characters', 'portrait', $character->character_id ?? $character->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
      {{ $character->name }}
    </a>
  @else
    <span>
      {!! img('characters', 'portrait', $character->character_id ?? $character->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
      {{ $character->name }}
    </span>
  @endif
@else
  {!! img('characters', 'portrait', $character->character_id ?? $character->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
  <span class="id-to-name" data-id="{{ $character->character_id ?? $character->entity_id }}">{{ trans('web::seat.unknown') }}</span>
@endif
