<h4>About</h4>

<div class="media">
  {!! img('types', 'render', $ship->type->typeID, 64, ['class' => 'align-self-center mr-3', 'alt' => $ship->type->typeName], false) !!}
  <div class="media-body">
    <h5 class="mt-0">{{ $ship->type->typeName }}</h5>
    <p class="text-justify">{!! $ship->type->description !!}</p>
  </div>
</div>

<h4>Financial</h4>

<div class="row mb-3">
  <div class="col-4">
    <dl>
      <dt>Hull Estimated Price</dt>
      <dd>{{ number($ship->type->price->adjusted_price) }}</dd>
    </dl>
  </div>
  <div class="col-4">
    <dl>
      <dt>Fitting Estimated Price</dt>
      <dd>{{ number($ship->content->sum(function ($item) { return $item->quantity * $item->type->price->adjusted_price; })) }}</dd>
    </dl>
  </div>
  <div class="col-4">
    <dl>
      <dt>Full Estimated Price</dt>
      <dd>{{ number($ship->type->price->adjusted_price + $ship->content->sum(function ($item) { return $item->quantity * $item->type->price->adjusted_price; })) }}</dd>
    </dl>
  </div>
</div>

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Sub-Systems Slots',
    'rows'  => $ship->content->filter(function ($item) { return strpos($item->location_flag, 'SubSystemSlot') !== false; }),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'High Slots',
    'rows'  => $ship->content->filter(function ($item) { return strpos($item->location_flag, 'HiSlot') !== false; }),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Medium Slots',
    'rows'  => $ship->content->filter(function ($item) { return strpos($item->location_flag, 'MedSlot') !== false; }),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Low Slots',
    'rows'  => $ship->content->filter(function ($item) { return strpos($item->location_flag, 'LoSlot') !== false; }),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Rigs Slots',
    'rows'  => $ship->content->filter(function ($item) { return strpos($item->location_flag, 'RigSlot') !== false; }),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Fleet Hangar',
    'rows'  => $ship->content->where('location_flag', 'FleetHangar'),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Services',
    'rows'  => $ship->content->filter(function ($item) { return strpos($item->location_flag, 'ServiceSlot') !== false; }),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Fighters Bay',
    'rows'  => $ship->content->where('location_flag', 'FighterBay'),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Fighter Tubes',
    'rows'  => $ship->content->filter(function ($item) { return strpos($item->location_flag, 'FighterTube') !== false; }),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Drones Bay',
    'rows'  => $ship->content->where('location_flag', 'DroneBay'),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Cargo',
    'rows'  => $ship->content->where('location_flag', 'Cargo'),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Fuel Bay',
    'rows'  => $ship->content->whereIn('location_flag', ['SpecializedFuelBay', 'StructureFuel']),
])
