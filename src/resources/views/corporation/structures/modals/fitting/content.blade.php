<h4>About</h4>

<div class="media">
  {!! img('types', 'render', $structure->type->typeID, 64, ['class' => 'align-self-center mr-3', 'alt' => $structure->type->typeName], false) !!}
  <div class="media-body">
    <h5 class="mt-0">{{ $structure->type->typeName }}</h5>
    <p class="text-justify">{!! $structure->type->description !!}</p>
  </div>
</div>

<div class="row">
  <div class="col-4">
    <dl>
      <dt>Activation Consumption</dt>
      <dd>{{ number_format($structure->activation_fuel_consumption, 0) }} fuel blocks</dd>
    </dl>
  </div>
  <div class="col-4">
    <dl>
      <dt>Hourly Consumption</dt>
      <dd>{{ number_format($structure->fuel_consumption, 0) }} fuel blocks</dd>
    </dl>
  </div>
  <div class="col-4">
    <dl>
      <dt>Monthly Consumption</dt>
      <dd>{{ number_format($structure->fuel_consumption * 24 * 30, 0) }} fuel blocks</dd>
    </dl>
  </div>
</div>
<div class="row mb-3">
  <div class="col-4">
    <dl>
      <dt>Location</dt>
      <dd>{{ $structure->solar_system->name }}</dd>
    </dl>
  </div>
  <div class="col-4">
    <dl>
      <dt>State</dt>
      <dd>{{ ucfirst(str_replace('_', ' ', $structure->state)) }}</dd>
    </dl>
  </div>
  <div class="col-4">
    <dl>
      <dt>Refuel Required in</dt>
      <dd>
        <span data-toggle="tooltip" title="{{ $structure->fuel_expires }}">
          @if(carbon()->addDay()->gte($structure->fuel_expires))
            <i class="fas fa-exclamation-circle text-danger"></i>
          @elseif(carbon()->addDays(2)->gte($structure->fuel_expires))
            <i class="fas fa-exclamation-triangle text-warning"></i>
          @else
            <i class="fas fa-check text-success"></i>
          @endif
          {{ human_diff($structure->fuel_expires) }}
          <i>({{ $structure->fuel }} Blocks)</i>
        </span>
      </dd>
    </dl>
  </div>
</div>

<h4>Financial</h4>

<div class="row mb-3">
  <div class="col-4">
    <dl>
      <dt>Hull Estimated Price</dt>
      <dd>{{ number_format($structure->type->price->adjusted_price) }}</dd>
    </dl>
  </div>
  <div class="col-4">
    <dl>
      <dt>Fitting Estimated Price</dt>
      <dd>{{ number_format($structure->fitting_estimated_price) }}</dd>
    </dl>
  </div>
  <div class="col-4">
    <dl>
      <dt>Full Estimated Price</dt>
      <dd>{{ number_format($structure->estimated_price) }}</dd>
    </dl>
  </div>
</div>

@include('web::corporation.structures.modals.fitting.slots', [
  'title' => 'High Slots',
  'rows' => $structure->high_slots,
])

@include('web::corporation.structures.modals.fitting.slots', [
  'title' => 'Medium Slots',
  'rows' => $structure->medium_slots,
])

@include('web::corporation.structures.modals.fitting.slots', [
  'title' => 'Low Slots',
  'rows' => $structure->low_slots,
])

@include('web::corporation.structures.modals.fitting.slots', [
  'title' => 'Rigs Slots',
  'rows' => $structure->rig_slots,
])

@include('web::corporation.structures.modals.fitting.slots', [
  'title' => 'Services Slots',
  'rows' => $structure->services_slots,
])

@include('web::corporation.structures.modals.fitting.slots', [
  'title' => 'Fighters Bay',
  'rows' => $structure->fighters_bay,
])

@include('web::corporation.structures.modals.fitting.slots', [
  'title' => 'Fuel Bay',
  'rows' => $structure->fuel_bay,
])

@include('web::corporation.structures.modals.fitting.slots', [
  'title' => 'Ammo Hold',
  'rows' => $structure->ammo_hold,
])
