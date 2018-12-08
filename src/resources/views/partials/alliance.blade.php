@if (request('all_linked_characters') === "true")
  {!! img('character', $character_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
@endif

{!! img('alliance', $alliance, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
@if (! is_null(cache('name_id:' . $alliance)))
  {{cache('name_id:' . $alliance)}}
@else
  <span class="id-to-name" data-id="{{$alliance}}">{{ trans('web::seat.unknown') }}</span>
@endif

