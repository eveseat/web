<legend>{{ $fitting->name }} ({{ $fitting->shiptype->typeName }})</legend>
<table class="table table-condensed table-hover table-responsive">
  <thead>
  <tr>
    <th>{{ trans('web::seat.quantity') }}</th>
    <th>{{ trans_choice('web::seat.type', 1) }}</th>
  </tr>
  </thead>
  <tbody>

  @foreach($items as $item)

    <tr>
      <td>{{ number_format($item->quantity, 0) }}</td>
      <td>
        @include('web::partials.type', ['type_id' => $item->type_id, 'type_name' => $item->type->typeName])
      </td>
    </tr>

  @endforeach

  </tbody>
</table>
