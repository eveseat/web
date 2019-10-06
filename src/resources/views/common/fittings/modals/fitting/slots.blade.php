@if($rows->isNotEmpty())
<h4 class="page-header">{{ $title }}</h4>
<table class="table table-condensed table-striped">
  <thead>
  <tr>
    <th>Name</th>
    <th>Quantity</th>
    <th>Estimated Price</th>
  </tr>
  </thead>
  <tbody>
  @foreach($rows as $row)
    <tr>
      <td>@include('web::partials.type', ['type_id' => $row->type->typeID, 'type_name' => $row->type->typeName])</td>
      <td>{{ number($row->quantity) }}</td>
      <td>{{ number($row->type->price->adjusted_price) }}</td>
    </tr>
  @endforeach
  </tbody>
  <tfoot>
  <tr>
    <th>Total</th>
    <th></th>
    <th>
      {{
        number($rows->sum(function ($item) {
            return $item->type->price->adjusted_price * $item->quantity;
          }))
      }}
    </th>
  </tr>
  </tfoot>
</table>
@endif