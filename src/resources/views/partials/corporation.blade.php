@if (request('all_linked_characters') === "true")
  {!! img('character', $character_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
@endif

@if (isset($corporation->name) )

  <a href="{{ route('corporation.view.summary', ['corporation_id' => $corporation->corporation_id]) }}">
    {!! img('corporation', $corporation->corporation_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
    {{$corporation->name}}
  </a>

@else

  {!! img('corporation', $corporation, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
  @if (! is_null(cache('name_id:' . $corporation)))
    {{cache('name_id:' . $corporation)}}
  @else
    <span class="id-to-name" data-id="{{$corporation}}">{{ trans('web::seat.unknown') }}</span>
  @endif

@endif