@if (request('all_linked_characters') === "true")
  {!! img('character', $character_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
@endif
{!! img('auto', $unknown_id, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
<span class="id-to-name" data-id="{{$unknown_id}}">{{ trans('web::seat.unknown') }}</span>