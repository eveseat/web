<h4>{{ $moon->itemName }}</h4>
<p class="lead">Provided figures are based on a chunk of one hour with {{ number_format(20000, 2) }} m3. Reprocessed figures are based on a 80% reprocessing yield.</p>

<h4>Raw Materials</h4>

<table class="table datatable table-striped">
  <thead>
    <tr>
      <th>Type</th>
      <th>Rate</th>
      <th>Volume</th>
      <th>Quantity</th>
      <th>Estimated Value</th>
    </tr>
  </thead>
  <tbody>
    @foreach($moon->moon_contents as $content)
      <tr>
        <td>
          @include('web::partials.type', ['type_id' => $content->type->typeID, 'type_name' => $content->type->typeName])
        </td>
        {{-- let's assume a default chunk is 20 000m3 per hour | https://wiki.eveuniversity.org/Moon_mining --}}
        <td>{{ number_format($content->rate * 100, 2) }} %</td>
        <td>{{ number_format($content->rate * 20000, 2) }} m3</td>
        <td>{{ number_format(($content->rate * 20000) / $content->type->volume) }}</td>
        <td>{{ number_format((($content->rate * 20000) / $content->type->volume) * $content->type->price->average, 2) }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

<h4>Refined Materials</h4>

<table class="table datatable table-striped">
  <thead>
    <tr>
      <th>Type</th>
      <th>Volume</th>
      <th>Quantity</th>
      <th>Estimated Value</th>
    </tr>
  </thead>
  <tbody>
    @foreach(
      $moon->moon_contents->map(function ($content) {
        return $content->type->materials->map(function ($material) use ($content) {
          // composite quantity = (moon rate * chunk volume) / composite volume
          // reprocessed quantity = composite quantity * material quantity / 100
          $material->quantity = intdiv(($content->rate * 20000) / $content->type->volume, 100) * $material->quantity * 0.80;
          return $material;
        });
      })->collapse()->groupBy('type.typeID') as $material
    )
      <tr>
        <td>
          @include('web::partials.type', ['type_id' => $material->first()->type->typeID, 'type_name' => $material->first()->type->typeName])
        </td>
        <td>{{ number_format($material->sum('quantity') * $material->first()->type->volume, 2) }} m3</td>
        <td>{{ number_format($material->sum('quantity')) }}</td>
        <td>{{ number_format($material->sum('quantity') * $material->first()->type->price->average) }}</td>
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
    @foreach($moon->moon_contents->map(function ($content) {
        return $content->type->materials->map(function ($material) use ($content) {
          // composite quantity = (composite volume * moon rate * chunk volume) / composite volume
          // reprocessed quantity = (composite quantity / 100) * quantity per 100
          $material->quantity = (($content->type->volume * $content->rate * 20000) / $content->type->volume) / 100 * $material->quantity;
          return $material;
        });
      })->collapse()->groupBy('type.typeID') as $material
    )
      @foreach($material->first()->type->reactions->groupBy('typeID')->flatten() as $reaction)
        <tr>
          <td>
            @include('web::partials.type', ['type_id' => $reaction->typeID, 'type_name' => $reaction->typeName, 'variation' => 'reaction'])
          </td>
          <td>{{ number_format($reaction->pivot->quantity) }}</td>
          <td>{{ number_format($reaction->pivot->quantity * $reaction->volume, 2) }}</td>
          <td>{{ number_format($reaction->pivot->quantity * $reaction->price->average) }}</td>
        </tr>
      @endforeach
    @endforeach
  </tbody>
</table>
