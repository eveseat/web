<h4 class="page-header">About</h4>
<div class="media">
  <div class="media-left">
    {!! img('type', $fitting->ship->typeID, 64, ['class' => 'media-object', 'alt' => $fitting->ship->typeName], false) !!}
  </div>
  <div class="media-body">
    <h4 class="media-heading">{{ $fitting->ship->typeName }}</h4>
    <p>{{ $fitting->ship->description }}</p>
  </div>
</div>

<h4 class="page-header">Financial</h4>
<table class="table table-condensed no-border">
  <thead>
    <tr>
      <th>Hull Estimated Price</th>
      <th>Fitting Estimated Price</th>
      <th>Full Estimated Price</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>{{ number($fitting->ship->price->adjusted_price) }}</td>
      <td>{{ number($fitting->fitting_estimated_price) }}</td>
      <td>{{ number($fitting->estimated_price) }}</td>
    </tr>
  </tbody>
</table>

@include('web::common.fittings.modals.fitting.slots', [
  'title' => 'Sub-Systems Slots',
  'rows' => $fitting->items->filter(function ($value) { return strpos($value->flag, 'SubSystemSlot') !== false; })
])

@include('web::common.fittings.modals.fitting.slots', [
  'title' => 'High Slots',
  'rows' => $fitting->items->filter(function ($value) { return strpos($value->flag, 'HiSlot') !== false; })
])

@include('web::common.fittings.modals.fitting.slots', [
  'title' => 'Medium Slots',
  'rows' => $fitting->items->filter(function ($value) { return strpos($value->flag, 'MedSlot') !== false; })
])

@include('web::common.fittings.modals.fitting.slots', [
  'title' => 'Low Slots',
  'rows' => $fitting->items->filter(function ($value) { return strpos($value->flag, 'LoSlot') !== false; })
])

@include('web::common.fittings.modals.fitting.slots', [
  'title' => 'Rigs Slots',
  'rows' => $fitting->items->filter(function ($value) { return strpos($value->flag, 'RigSlot') !== false; })
])

@include('web::common.fittings.modals.fitting.slots', [
  'title' => 'Fighters Bays',
  'rows' => $fitting->items->where('flag', 'FighterBay')
])

@include('web::common.fittings.modals.fitting.slots', [
  'title' => 'Drones Bays',
  'rows' => $fitting->items->where('flag', 'DroneBay')
])

@include('web::common.fittings.modals.fitting.slots', [
  'title' => 'Cargo',
  'rows' => $fitting->items->where('flag', 'Cargo')
])
