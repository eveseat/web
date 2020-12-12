@if(! is_null($alliance) && (! is_null($alliance->entity_id) || ! is_null($alliance->alliance_id)))
  @if($alliance->name && $alliance->name != trans('web::seat.unknown'))
      {!! img('alliances', 'logo', $alliance->alliance_id ?? $alliance->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
      <span>{{ $alliance->name }}</span>
  @else
    {!! img('alliances', 'logo', $alliance->alliance_id ?? $alliance->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
    <span class="id-to-name" data-id="{{ $alliance->alliance_id ?? $alliance->entity_id }}">{{ trans('web::seat.unknown') }}</span>
  @endif
@endif
