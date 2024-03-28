<h4>{{ trans('web::mining.metadata') }}</h4>

<table class="table table-sm table-condensed no-border">
  <thead>
    <tr>
      <th class="text-center">{{ trans('web::mining.date') }}</th>
      <th class="text-center">{{ trans('web::mining.system') }}</th>
      <th class="text-center">{{ trans('web::mining.ore') }}</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="text-center">
        @include('web::partials.date', ['datetime' => $entries->first()->date])
      </td>
      <td class="text-center">
        @include('web::partials.system', ['system' => $entries->first()->solar_system->name, 'security' => $entries->first()->solar_system->security])
      </td>
      <td class="text-center">
        @include('web::partials.type', ['type_id' => $entries->first()->type->typeID, 'type_name' => $entries->first()->type->typeName])
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
    @foreach($entries->first()->type->materials as $type)
      <tr>
        <td>
          @include('web::partials.type', ['type_id' => $type->typeID, 'type_name' => $type->typeName])
        </td>
        <td>{{ number_format(intdiv($entries->sum('quantity'), 100) * $type->pivot->quantity * 0.80) }}</td>
        <td>{{ number_format(intdiv($entries->sum('quantity'), 100) * $type->pivot->quantity * 0.80 * $type->volume, 2) }}</td>
        <td>{{ number_format(intdiv($entries->sum('quantity'), 100) * $type->pivot->quantity * 0.80 * $type->price->adjusted_price, 2) }}</td>
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
        <td>{{ number_format($entry->quantity * $entry->type->price->adjusted_price, 2) }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
