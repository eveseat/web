@if (request('all_linked_characters') === "true")
  {!! img('character', $character_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
@endif

@if (isset($character->name))

  <a href="{{ route('character.view.sheet', ['character_id' => $character->character_id]) }}">
    {!! img('character', $character->character_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
    {{$character->name}}
  </a>

@else

  {!! img('character', $character, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
  @if (! is_null(cache('name_id:' . $character)))
    {{cache('name_id:' . $character)}}
  @else
    <span class="id-to-name" data-id="{{$character}}">{{ trans('web::seat.unknown') }}</span>
  @endif

@endif