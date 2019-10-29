<h4>{{ trans('web::mining.metadata') }}</h4>

<table class="table table-sm table-condensed no-border">
  <thead>
    <tr>
      <th class="col-md-4 text-center">{{ trans('web::mining.date') }}</th>
      <th class="col-md-4 text-center">{{ trans('web::mining.system') }}</th>
      <th class="col-md-4 text-center">{{ trans('web::mining.ore') }}</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="text-center">
        @include('web::partials.date', ['datetime' => $entries->first()->date])
      </td>
      <td class="text-center">
        @include('web::partials.system', ['system' => $entries->first()->system->itemName, 'security' => $entries->first()->system->security])
      </td>
      <td class="text-center">
        {!! img('type', $entries->first()->type->typeID, 32, ['class' => 'img-circle eve-icon'], false) !!}
        {{ $entries->first()->type->typeName }}
      </td>
    </tr>
  </tbody>
</table>

<h4>{{ trans('web::mining.compounds') }}</h4>

<table class="table table-sm table-condensed table-striped">
  <thead>
    <tr>
      <th>{{ trans('web::mining.type') }}</th>
      <th>{{ trans('web::mining.quantity') }}</th>
      <th>{{ trans('web::mining.volume') }}</th>
      <th>{{ trans('web::mining.estimated_value') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($entries->first()->type->materials as $material)
      <tr>
        <td>
          {!! img('type', $material->type->typeID, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
          {{ $material->type->typeName }}
        </td>
        <td>{{ number_format($entries->sum('quantity') * $material->quantity / 100, 0) }}</td>
        <td>{{ number_format($entries->sum('quantity') * $material->quantity * $material->type->volume / 100, 2) }}</td>
        <td>{{ number_format($entries->sum('quantity') * $material->quantity * $material->type->price->average_price / 100, 2) }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

<h4>Metrics</h4>

<table class="table table-sm table-condensed table-striped">
  <thead>
    <tr>
      <th>{{ trans('web::mining.time') }}</th>
      <th>{{ trans('web::mining.quantity') }}</th>
      <th>{{ trans('web::mining.volume') }}</th>
      <th>{{ trans('web::mining.estimated_value') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($entries as $entry)
      <tr>
        <td>{{ $entry->time }}</td>
        <td>{{ number_format($entry->quantity, 0) }}</td>
        <td>{{ number_format($entry->quantity * $entry->type->volume, 2) }}</td>
        <td>{{ number_format($entry->quantity * $entry->type->price->average_price, 2) }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
