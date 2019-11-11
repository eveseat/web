@if ($corporation->name && $corporation->name !== trans('web::seat.unknown'))
  <a href="{{ route('corporation.view.default', ['corporation_id' => $corporation->corporation_id ?? $corporation->entity_id]) }}">
    {!! img('corporations', 'logo', $corporation->corporation_id ?? $corporation->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
    {{ $corporation->name }}
  </a>
@else
  {!! img('corporations', 'logo', $corporation->corporation_id ?? $corporation->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
  {!!
    cache(sprintf('name_id:%s', $corporation->corporation_id ?? $corporation->entity_id), function () use ($corporation) {
      return sprintf('<span class="id-to-name" data-id="%d">%s</span>', $corporation->corporation_id ?? $corporation->entity_id, trans('web::seat.unknown'));
    })
  !!}
@endif
