<h4>About</h4>

<div class="media">
  {!! img('types', 'render', $asset->type->typeID, 64, ['class' => 'align-self-center mr-3', 'alt' => $asset->type->typeName], false) !!}
  <div class="media-body">
    <h5 class="mt-0">{{ $asset->type->typeName }}</h5>
    <p class="text-justify">{!! $asset->type->description !!}</p>
  </div>
</div>

<h4>Financial</h4>

<div class="row mb-3">
  <div class="col-4">
    <dl>
      <dt>Hull Estimated Price</dt>
      <dd>{{ number($asset->type->price->adjusted_price) }}</dd>
    </dl>
  </div>
  <div class="col-4">
    <dl>
      <dt>Fitting Estimated Price</dt>
      <dd>{{ number($asset->content->sum(function ($item) { return $item->quantity * $item->type->price->adjusted_price; })) }}</dd>
    </dl>
  </div>
  <div class="col-4">
    <dl>
      <dt>Full Estimated Price</dt>
      <dd>{{ number($asset->type->price->adjusted_price + $asset->content->sum(function ($item) { return $item->quantity * $item->type->price->adjusted_price; })) }}</dd>
    </dl>
  </div>
</div>

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Sub-Systems Slots',
    'rows'  => $asset->content->filter(function ($item) { return strpos($item->location_flag, 'SubSystemSlot') !== false; }),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'High Slots',
    'rows'  => $asset->content->filter(function ($item) { return strpos($item->location_flag, 'HiSlot') !== false; }),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Medium Slots',
    'rows'  => $asset->content->filter(function ($item) { return strpos($item->location_flag, 'MedSlot') !== false; }),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Low Slots',
    'rows'  => $asset->content->filter(function ($item) { return strpos($item->location_flag, 'LoSlot') !== false; }),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Rigs Slots',
    'rows'  => $asset->content->filter(function ($item) { return strpos($item->location_flag, 'RigSlot') !== false; }),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Fleet Hangar',
    'rows'  => $asset->content->where('location_flag', 'FleetHangar'),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Services',
    'rows'  => $asset->content->filter(function ($item) { return strpos($item->location_flag, 'ServiceSlot') !== false; }),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Fighters Bay',
    'rows'  => $asset->content->where('location_flag', 'FighterBay'),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Fighter Tubes',
    'rows'  => $asset->content->filter(function ($item) { return strpos($item->location_flag, 'FighterTube') !== false; }),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Drones Bay',
    'rows'  => $asset->content->where('location_flag', 'DroneBay'),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Cargo',
    'rows'  => $asset->content->where('location_flag', 'Cargo'),
])

@include('web::common.assets.modals.fitting.slots', [
    'title' => 'Fuel Bay',
    'rows'  => $asset->content->whereIn('location_flag', ['SpecializedFuelBay', 'StructureFuel']),
])
