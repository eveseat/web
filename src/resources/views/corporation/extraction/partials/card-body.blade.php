<h5>
  <i class="fa fa-map"></i>
  @if (! is_null($column->moon) && ! is_null($column->moon->region))
    {{ $column->moon->region->name }}
    @if (! is_null($column->moon->constellation))
      | {{ $column->moon->constellation->name }}
    @else
      {{ sprintf('%s %s', trans('web::seat.unknown'), trans('web::seat.constellation')) }}
    @endif
    @if (! is_null($column->moon->solar_system))
      | {{ $column->moon->solar_system->name }}
    @else
      {{ sprintf('%s %s', trans('web::seat.unknown'), trans('web::seat.system')) }}
    @endif
  @else
    {{ sprintf('%s %s', trans('web::seat.unknown'), trans('web::seat.region')) }}
  @endif
</h5>
<div class="text-muted">
  <i class="fas fa-globe"></i>
  @if (! is_null($column->moon))
    {{ $column->moon->name }}
  @else
    {{ trans('web::seat.unknown') }}
  @endif
</div>
<div class="text-muted">
    <i class="fas fa-monument"></i> {{ $column->moon->extraction->structure->info->name }}
</div>
<hr/>
<dl class="dl-horizontal">
  <dt>{{ trans('web::seat.start_at') }}</dt>
  <dd>{{ $column->moon->extraction->extraction_start_time }}</dd>
  <dt>{{ trans('web::seat.chunk_arrival') }}</dt>
  <dd>{{ $column->moon->extraction->chunk_arrival_time }}</dd>
  <dt>{{ trans('web::seat.self_destruct') }}</dt>
  <dd>{{ $column->moon->extraction->natural_decay_time }}</dd>
</dl>
@if (! $column->content->isEmpty())
<hr/>
<table class="table table-striped">
  <thead>
    <tr>
      <th>{{ trans_choice('web::seat.type', 1) }}</th>
      <th>{{ trans('web::seat.rate') }}</th>
      <th>{{ trans('web::seat.rarity') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($column->content as $type)
      <tr>
        <td>
          @include('web::partials.type', ['type_id' => $type->typeID, 'type_name' => $type->typeName])
        </td>
        <td>{{ number_format($type->pivot->rate * 100) }} %</td>
        <td>
          @switch($type->marketGroupID)
            @case(2396)
              <span class="badge badge-success">R4</span>
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
