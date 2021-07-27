@if(! is_null($alliance) && (! is_null($alliance->entity_id) || ! is_null($alliance->alliance_id)))
  @if($alliance->name && $alliance->name != trans('web::seat.unknown'))
    <a href="{{ route('alliance.view.default', ['alliance' => $alliance->alliance_id ?? $alliance->entity_id]) }}">
      {!! img('alliances', 'logo', $alliance->alliance_id ?? $alliance->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
      <span>{{ $alliance->name }}</span>
    </a>
  @else
    {!! img('alliances', 'logo', $alliance->alliance_id ?? $alliance->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
    <span class="id-to-name" data-id="{{ $alliance->alliance_id ?? $alliance->entity_id }}">{{ trans('web::seat.unknown') }}</span>
  @endif
@endif
