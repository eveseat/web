<h4>{{ $moon->itemName }}</h4>

<h4>Raw Materials</h4>

<table class="table datatable table-striped">
  <thead>
    <tr>
      <th>Type</th>
      <th>Rate</th>
      <th>Volume</th>
      <th>Estimated Value</th>
    </tr>
  </thead>
  <tbody>
    @foreach($moon->moon_contents as $content)
      <tr>
        <td>
          @include('web::partials.type', ['type_id' => $content->type->typeID, 'type_name' => $content->type->typeName])
        </td>
        <td>{{ number_format($content->rate * 100, 2) }} %</td>
        <td>{{ number_format($content->type->volume, 2) }} m3</td>
        <td>{{ number_format($content->type->price->average_price) }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

<h4>Refined Materials</h4>

<table class="table datatable table-striped">
  <thead>
    <tr>
      <th>Type</th>
      <th>Quantity</th>
      <th>Volume</th>
      <th>Estimated Value</th>
    </tr>
  </thead>
  <tbody>
    @foreach($moon->moon_contents->map(function ($content) { return $content->type->materials; })->collapse()->groupBy('type.typeID') as $material)
      <tr>
        <td>
          @include('web::partials.type', ['type_id' => $material->first()->type->typeID, 'type_name' => $material->first()->type->typeName])
        </td>
        <td>{{ number_format($material->sum('quantity')) }}</td>
        <td>{{ number_format($material->sum('quantity') * $material->first()->type->volume, 2) }}</td>
        <td>{{ number_format($material->sum('quantity') * $material->first()->type->price->average_price) }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

<h4>Reactions Candidates</h4>

<table class="table datatable table-striped">
  <thead>
    <tr>
      <th>Type</th>
      <th>Quantity</th>
      <th>Volume</th>
      <th>Estimated Value</th>
    </tr>
  </thead>
  <tbody>
    @foreach($moon->moon_contents->map(function ($content) { return $content->type->materials; })->collapse()->groupBy('type.typeID') as $material)
      @foreach($material->first()->type->reactions->groupBy('typeID')->flatten() as $reaction)
        <tr>
          <td>
            @include('web::partials.type', ['type_id' => $reaction->typeID, 'type_name' => $reaction->typeName, 'variation' => 'reaction'])
          </td>
          <td>{{ number_format($reaction->pivot->quantity) }}</td>
          <td>{{ number_format($reaction->pivot->quantity * $reaction->volume, 2) }}</td>
          <td>{{ number_format($reaction->pivot->quantity * $reaction->price->average_price) }}</td>
        </tr>
      @endforeach
    @endforeach
  </tbody>
</table>
