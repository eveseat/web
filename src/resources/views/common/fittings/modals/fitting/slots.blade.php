@if($rows->isNotEmpty())
<h4>{{ $title }}</h4>

<table class="table table-sm table-striped">
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
      <td class="w-50">@include('web::partials.type', ['type_id' => $row->type->typeID, 'type_name' => $row->type->typeName, 'variation' => $row->type->group->categoryID == 9 ? 'bpc' : 'icon'])</td>
      <td class="w-25">{{ number($row->quantity) }}</td>
      <td class="w-25">{{ number($row->type->price->adjusted_price) }}</td>
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