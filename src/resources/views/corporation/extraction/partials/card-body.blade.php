<h4>
  <i class="fa fa-map"></i>
  @if (! is_null($extraction->moon) && ! is_null($extraction->moon->region))
    {{ $extraction->moon->region->name }}
    @if (! is_null($extraction->moon->constellation))
      | {{ $extraction->moon->constellation->name }}
    @else
      {{ sprintf('%s %s', trans('web::seat.unknown'), trans('web::seat.constellation')) }}
    @endif
    @if (! is_null($extraction->moon->system))
      | {{ $extraction->moon->system->name }}
    @else
      {{ sprintf('%s %s', trans('web::seat.unknown'), trans('web::seat.system')) }}
    @endif
  @else
    {{ sprintf('%s %s', trans('web::seat.unknown'), trans('web::seat.region')) }}
  @endif
</h4>
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
@if (! $extraction->moon->moon_contents->isEmpty())
<table class="table table-striped">
  <thead>
    <tr>
      <th>{{ trans_choice('web::seat.type', 1) }}</th>
      <th>{{ trans('web::seat.rate') }}</th>
      <th>{{ trans('web::seat.rarity') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($extraction->moon->moon_contents as $content)
    @if(! is_null($content->type))
    <tr>
      <td>{!! img('type', $content->type->typeID, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!} {{ $content->type->typeName }}</td>
      <td>{{ number($content->rate * 100) }} %</td>
      <td>
        @switch($content->type->marketGroupID)
          @case(2396)
            <span class="label label-success">Gaz</span>
            @break
          @case(2397)
            <span class="label label-primary">R8</span>
            @break
          @case(2398)
            <span class="label label-info">R16</span>
            @break
          @case(2400)
            <span class="label label-warning">R32</span>
            @break
          @case(2401)
            <span class="label label-danger">R64</span>
            @break
          @default
            <span class="label label-default">ORE</span>
        @endswitch
      </td>
    </tr>
    @endif
    @endforeach
  </tbody>
</table>
@endif