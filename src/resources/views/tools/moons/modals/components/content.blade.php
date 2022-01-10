<h4>{{ $moon->name }}</h4>
<p class="lead">Provided figures are based on a chunk of one hour with {{ number_format(40000, 2) }} m3. Reprocessed figures are based on a {{ (setting('reprocessing_yield') ?: 0.80) * 100 }}% reprocessing yield.</p>

<h4>Raw Materials</h4>

<table class="table datatable table-striped" id="rawMaterials">
  <thead>
    <tr>
      <th>Type</th>
      <th>Rate</th>
      <th>Volume (monthly)</th>
      <th>Quantity (monthly)</th>
      <th>Estimated Value (monthly)</th>
    </tr>
  </thead>
  <tbody>
    @foreach($moon->content as $type)
      <tr>
        <td>
          @include('web::partials.type', ['type_id' => $type->typeID, 'type_name' => $type->typeName])
        </td>
        {{-- let's assume a default chunk is 40 000m3 per hour | https://wiki.eveuniversity.org/Moon_mining --}}
        <td>{{ number_format($type->pivot->rate * 100, 2) }} %</td>
        <td>{{ number_format($type->pivot->rate * 40000 * 720, 2) }} m3</td>
        <td>{{ number_format(($type->pivot->rate * 40000 * 720) / $type->volume) }}</td>
        <td>{{ number_format((($type->pivot->rate * 40000 * 720) / $type->volume) * $type->price->average, 2) }}</td>
      </tr>
    @endforeach
      <tfoot>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
      </tfoot>
  </tbody>
</table>

<h4>Refined Materials</h4>

<table class="table datatable table-striped" id="refinedMaterials">
  <thead>
    <tr>
      <th>Type</th>
      <th>Volume (monthly)</th>
      <th>Quantity (monthly)</th>
      <th>Estimated Value (monthly)</th>
    </tr>
  </thead>
  <tbody>
    @foreach(
      $moon->content->map(function ($type) {
        return $type->materials->map(function ($material) use ($type) {
          // composite quantity = (moon rate * chunk volume) / composite volume
          // reprocessed quantity = composite quantity * material quantity / 100
          $material->pivot->quantity = intdiv(($type->pivot->rate * 40000 * 720) / $type->volume, 100) * $material->pivot->quantity * (setting('reprocessing_yield') ?: 0.80);
          return $material;
        });
      })->collapse()->groupBy('typeID') as $material
    )
      <tr>
        <td>
          @include('web::partials.type', ['type_id' => $material->first()->typeID, 'type_name' => $material->first()->typeName])
        </td>
        <td>{{ number_format($material->sum('pivot.quantity') * $material->first()->volume, 2) }} m3</td>
        <td>{{ number_format($material->sum('pivot.quantity')) }}</td>
        <td>{{ number_format($material->sum('pivot.quantity') * $material->first()->price->average) }}</td>
      </tr>
    @endforeach
      <tfoot>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
      </tfoot>
  </tbody>
</table>

<h4>Reactions Candidates</h4>

<table class="table datatable table-striped" id="reactionsCandidates">
  <thead>
    <tr>
      <th>Type</th>
      <th>Components</th>
    </tr>
  </thead>
  <tbody>
    @foreach($moon->content->filter(function ($type) {
        if (! $type->materials)
            return false;

        return $type->materials->filter(function ($material) {
            return $material->reactions !== null;
        })->isNotEmpty();
    })->map(function ($type) {
        return $type->materials->map(function ($material) {
            return $material->reactions;
        })->flatten();
    })->flatten()->unique('typeName') as $reaction)
      <tr>
        <td>
          @include('web::partials.type', ['type_id' => $reaction->typeID, 'type_name' => $reaction->typeName, 'variation' => 'reaction'])
        </td>
        <td>
          {!! $reaction->components->sortBy('typeName')->map(function ($type) {
              return view('web::partials.type', ['type_id' => $type->typeID, 'type_name' => $type->typeName])->render();
          })->join(' ') !!}
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
