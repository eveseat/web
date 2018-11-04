
@if( isset($corporation->name) )

  <a href="{{ route('character.view.sheet', ['character_id' => $corporation->character_id]) }}">
    {!! img('corporation', $corporation->character_id, 32, ['class' => 'img-circle eve-icon small-icon'],false) !!}
    {{$corporation->name}}
  </a>

@else

  {!! img('corporation', $corporation, 32, ['class' => 'img-circle eve-icon small-icon'],false) !!}
  @if(! is_null(cache('name_id:' . $corporation)))
    {{cache('name_id:' . $corporation)}}
  @else
    <span class="id-to-name" data-id="{{$corporation}}">{{ trans('web::seat.unknown') }}</span>
  @endif

@endif