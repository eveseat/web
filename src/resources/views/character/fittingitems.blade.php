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
      <td>{{ number($item->quantity, 0) }}</td>
      <td>
        {!! img('type', $item->type_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
        {{ $item->type->typeName }}
      </td>
    </tr>

  @endforeach

  </tbody>
</table>
