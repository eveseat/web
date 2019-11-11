@if(! is_null($faction->entity_id))
  {!! img('factions', 'logo', $faction->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
  {!!
    cache(sprintf('name_id:%s', $faction->entity_id), function () use ($faction) {
      return sprintf('<span class="id-to-name" data-id="%d">%s</span>', $faction->entity_id, trans('web::seat.unknown'));
    })
  !!}
@endif