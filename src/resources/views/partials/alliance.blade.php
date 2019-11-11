@if(! is_null($alliance) && (! is_null($alliance->entity_id) || ! is_null($alliance->alliance_id)))
  {!! img('alliances', 'logo', $alliance->alliance_id ?? $alliance->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
  {!!
    cache(sprintf('name_id:%s', $alliance->alliance_id ?? $alliance->entity_id), function () use ($alliance) {
      return sprintf('<span class="id-to-name" data-id="%d">%s</span>', $alliance->alliance_id ?? $alliance->entity_id, trans('web::seat.unknown'));
    })
  !!}
@endif
