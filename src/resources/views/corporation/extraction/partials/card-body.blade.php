@if (! is_null($column->structure) && ! is_null($column->structure->info))
<h5>
<i class="fas fa-monument"></i>
{{ $column->structure->info->name }}
</h5>
<h6>
<i class="fa fa-map"></i>
  @if (! is_null($column->moon) && ! is_null($column->moon->region))
    {{ $column->moon->region->name }}
  @else
    {{ sprintf('%s %s', trans('web::seat.unknown'), trans('web::seat.region')) }}
  @endif
</h6>

@else
<h5>
<i class="fas fa-monument"></i>
{{ sprintf('%s %s', trans('web::seat.unknown'), trans_choice('web::seat.structure', 1)) }}
</h5>
<h6>
<i class="fa fa-map"></i>
  @if (! is_null($column->moon) && ! is_null($column->moon->region))
    {{ $column->moon->region->name }}
    @if (! is_null($column->moon->solar_system))
      | {{ $column->moon->solar_system->name }}
    @else
      {{ sprintf('%s %s', trans('web::seat.unknown'), trans('web::seat.system')) }}
    @endif
  @else
    {{ sprintf('%s %s', trans('web::seat.unknown'), trans('web::seat.region')) }}
  @endif
</h6>
@endif


<div class="text-muted">
  <i class="fas fa-globe"></i>
  @if (! is_null($column->moon))
    {{ $column->moon->name }}
  @else
    {{ trans('web::seat.unknown') }}
  @endif
</div>


<hr/>

<dl class="row">
  <dt class="col-lg-5">{{ trans('web::seat.drill_start') }}</dt>
  <dd class="col-lg-7">{{ substr($column->extraction_start_time, 0, -3) }}</dd>
  <dt class="col-lg-5">{{ trans('web::seat.chunk_arrival') }}</dt>
  <dd class="col-lg-7">{{ substr($column->chunk_arrival_time, 0, -3) }}</dd>
  <dt class="col-lg-5">{{ trans('web::seat.auto_fracture') }}</dt>
  <dd class="col-lg-7">{{ substr($column->natural_decay_time, 0, -3) }}</dd>
</dl>

@if (! is_null($column->moon)  && ! is_null($column->moon->moon_report) && ! $column->moon->moon_report->content->isEmpty())
<div class="collapse rate-collapse">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>{{ trans_choice('web::seat.type', 1) }}</th>
        <th>{{ trans('web::seat.volume') }}</th>
        <th>{{ trans('web::seat.rarity') }}</th>
      </tr>
    </thead>
    <tbody>
      @foreach($column->moon->moon_report->content as $type)
        <tr>
          <td>
            @include('web::partials.type', ['type_id' => $type->typeID, 'type_name' => $type->typeName])
          </td>
          <td>{{ number_format($type->pivot->rate * $column->volume()) }} m3</td>
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
      @for($i = 0; $i < (4 - $column->moon->moon_report->content->count()); $i++)
      <tr>
        <td>-</td>
        <td>-</td>
        <td>-</td>
      </tr>
      @endfor
      <tfoot>
        <th>Total</th>
        <th>{{ number_format($column->moon->moon_report->content->sum('pivot.rate') * $column->volume()) }} m3</th>
        <th></th>
      </tfoot>
    </tbody>
  </table>
</div>
@endif
