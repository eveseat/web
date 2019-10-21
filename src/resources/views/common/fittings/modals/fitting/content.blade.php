<h4>About</h4>

<div class="media">
  {!! img('type', $fitting->ship->typeID, 64, ['class' => 'align-self-center mr-3', 'alt' => $fitting->ship->typeName], false) !!}
  <div class="media-body">
    <h5 class="mt-0">{{ $fitting->ship->typeName }}</h5>
    <p class="text-justify">{!! $fitting->ship->description !!}</p>
  </div>
</div>

<h4>Financial</h4>

<table class="table table-sm no-border">
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
  'rows' => $fitting->sub_systems,
])

@include('web::common.fittings.modals.fitting.slots', [
  'title' => 'High Slots',
  'rows' => $fitting->high_slots,
])

@include('web::common.fittings.modals.fitting.slots', [
  'title' => 'Medium Slots',
  'rows' => $fitting->medium_slots,
])

@include('web::common.fittings.modals.fitting.slots', [
  'title' => 'Low Slots',
  'rows' => $fitting->low_slots,
])

@include('web::common.fittings.modals.fitting.slots', [
  'title' => 'Rigs Slots',
  'rows' => $fitting->rig_slots,
])

@include('web::common.fittings.modals.fitting.slots', [
  'title' => 'Fighters Bay',
  'rows' => $fitting->fighters_bay,
])

@include('web::common.fittings.modals.fitting.slots', [
  'title' => 'Drones Bay',
  'rows' => $fitting->drones_bay,
])

@include('web::common.fittings.modals.fitting.slots', [
  'title' => 'Cargo',
  'rows' => $fitting->cargo,
])
