<h5>
  <i class="fa fa-map"></i>
  @if (! is_null($extraction->moon) && ! is_null($extraction->moon->region))
    {{ $extraction->moon->region->name }}
    @if (! is_null($extraction->moon->constellation))
      | {{ $extraction->moon->constellation->name }}
    @else
      {{ sprintf('%s %s', trans('web::seat.unknown'), trans('web::seat.constellation')) }}
    @endif
    @if (! is_null($extraction->moon->solar_system))
      | {{ $extraction->moon->solar_system->name }}
    @else
      {{ sprintf('%s %s', trans('web::seat.unknown'), trans('web::seat.system')) }}
    @endif
  @else
    {{ sprintf('%s %s', trans('web::seat.unknown'), trans('web::seat.region')) }}
  @endif
</h5>
<span class="text-muted">
  <i class="fa fa-globe"></i>
  @if (! is_null($extraction->moon))
    {{ $extraction->moon->name }}
  @else
    {{ trans('web::seat.unknown') }}
  @endif
</span>
<hr/>
<dl class="dl-horizontal">
  <dt>{{ trans('web::seat.start_at') }}</dt>
  <dd>{{ $extraction->extraction_start_time }}</dd>
  <dt>{{ trans('web::seat.chunk_arrival') }}</dt>
  <dd>{{ $extraction->chunk_arrival_time }}</dd>
  <dt>{{ trans('web::seat.self_destruct') }}</dt>
  <dd>{{ $extraction->natural_decay_time }}</dd>
</dl>
@if (! $extraction->moon->content->isEmpty())
<table class="table table-striped">
  <thead>
    <tr>
      <th>{{ trans_choice('web::seat.type', 1) }}</th>
      <th>{{ trans('web::seat.rate') }}</th>
      <th>{{ trans('web::seat.rarity') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($extraction->moon->content as $type)
      <tr>
        <td>
          @include('web::partials.type', ['type_id' => $type->typeID, 'type_name' => $type->typeName])
        </td>
        <td>{{ number_format($type->pivot->rate * 100) }} %</td>
        <td>
          @switch($type->marketGroupID)
            @case(2396)
              <span class="badge badge-success">Gaz</span>
              @break
            @case(2397)
              <span class="badge badge-primary">R8</span>
              @break
            @case(2398)
              <span class="badge badge-info">R16</span>
              @break
            @case(2400)
              <span class="badge badge-warning">R32</span>
              @break
            @case(2401)
              <span class="badge badge-danger">R64</span>
              @break
            @default
              <span class="badge badge-default">ORE</span>
          @endswitch
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
@endif