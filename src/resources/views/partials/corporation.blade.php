@if($corporation===null)
  {!! img('corporations', 'logo', null, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
  <span>{{ trans('web::seat.unknown') }}</span>
@elseif ($corporation->name && $corporation->name !== trans('web::seat.unknown'))
  @if(\Seat\Eveapi\Models\Corporation\CorporationInfo::find($corporation->corporation_id ?? $corporation->entity_id))
  <a href="{{ route('corporation.view.default', ['corporation' => $corporation->corporation_id ?? $corporation->entity_id]) }}">
    {!! img('corporations', 'logo', $corporation->corporation_id ?? $corporation->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
    {{ $corporation->name }}
  </a>
  @else
    <span>
      {!! img('corporations', 'logo', $corporation->corporation_id ?? $corporation->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
      {{ $corporation->name }}
    </span>
  @endif
@else
  {!! img('corporations', 'logo', $corporation->corporation_id ?? $corporation->entity_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
  <span class="id-to-name" data-id="{{ $corporation->corporation_id ?? $corporation->entity_id }}">{{ trans('web::seat.unknown') }}</span>
@endif
