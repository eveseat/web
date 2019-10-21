@if (request('all_linked_characters') === "true")
  {!! img('character', $character_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
@endif

@if ($corporation->name && $corporation->name !== trans('web::seat.unknown'))

  <a href="{{ route('corporation.view.default', ['corporation_id' => $corporation->corporation_id ?? $corporation->entity_id]) }}">
    {!! img('corporation', $corporation->corporation_id ?? $corporation->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
    {{ $corporation->name }}
  </a>

@else

  {!! img('corporation', $corporation->corporation_id ?? $corporation->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
  @if (! is_null(cache('name_id:' . $corporation->corporation_id ?? $corporation->entity_id)))
    {{cache('name_id:' . $corporation->corporation_id ?? $corporation->entity_id)}}
  @else
    <span class="id-to-name" data-id="{{ $corporation->corporation_id ?? $corporation->entity_id }}">{{ trans('web::seat.unknown') }}</span>
  @endif

@endif
