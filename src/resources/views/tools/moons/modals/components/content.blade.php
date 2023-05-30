<h4>{{ $moon->moon->name }}</h4>
<p class="lead">
  {{ trans('web::moons.yield_explanation',['volume'=>number_format(Seat\Eveapi\Models\Industry\CorporationIndustryMiningExtraction::BASE_DRILLING_VOLUME, 2),'yield'=>(setting('reprocessing_yield') ?: 0.80) * 100]) }}
</p>

{{-- https://www.eveonline.com/news/view/moon-mineral-distribution-update --}}
@if(carbon($moon->last_updated)->lt(carbon('2020-03-30')))
  <div class='alert alert-warning'>
    {{ trans('web::moons.outdated_data_warning') }}
    <a href='https://www.eveonline.com/news/view/moon-mineral-distribution-update' target='_blank'>{{ trans('web::moons.outdated_data_devblog_link') }}</a>
  </div>
@endif

<h4>Details</h4>
<dl>
  <dt>{{ trans_choice('web::moons.moon', 1) }}</dt>
  <dd>{{ $moon->moon->name }}</dd>

  <dt>{{trans_choice('web::moons.region', 1)}}</dt>
  <dd>{{ $moon->moon->region->name }}</dd>

  <dt>{{trans_choice('web::moons.constellation', 1)}}</dt>
  <dd>{{ $moon->moon->constellation->name }}</dd>

  <dt>{{ trans_choice('web::moons.sovereignty', 1) }}</dt>
  <dd>@include('web::partials.sovereignty', ['sovereignty' => $moon->moon->solar_system->sovereignty])</dd>

  <dt>{{ trans('web::seat.last_updated') }}</dt>
  <dd>@include('web::partials.date', ['datetime' => $moon->updated_at])</dd>
</dl>

<h4>{{ trans('web::moons.raw_materials') }}</h4>

<table class="table datatable table-striped" id="rawMaterials">
  <thead>
    <tr>
      <th>{{ trans_choice('web::seat.type', 1) }}</th>
      <th>{{ trans('web::seat.rate') }}</th>
      <th>{{ trans('web::moons.monthly_volume') }}</th>
      <th>{{ trans('web::moons.monthly_quantity') }}</th>
      <th>{{ trans('web::moons.monthly_estimated_value') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($moon->content as $type)
      <tr>
        <td>
          @include('web::partials.type', ['type_id' => $type->typeID, 'type_name' => $type->typeName])
        </td>
        <td>{{ number_format($type->pivot->rate * 100, 2) }} %</td>
        <td>{{ number_format($type->pivot->rate * Seat\Eveapi\Models\Industry\CorporationIndustryMiningExtraction::BASE_DRILLING_VOLUME * 720, 2) }} m3</td>
        <td>{{ number_format(($type->pivot->rate * Seat\Eveapi\Models\Industry\CorporationIndustryMiningExtraction::BASE_DRILLING_VOLUME * 720) / $type->volume) }}</td>
        <td>{{ number_format((($type->pivot->rate * Seat\Eveapi\Models\Industry\CorporationIndustryMiningExtraction::BASE_DRILLING_VOLUME * 720) / $type->volume) * $type->price->adjusted_price, 2) }}</td>
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

<h4>{{ trans('web::moons.refined_materials') }}</h4>

<table class="table datatable table-striped" id="refinedMaterials">
  <thead>
    <tr>
      <th>{{ trans_choice('web::seat.type', 1) }}</th>
      <th>{{ trans('web::moons.monthly_volume') }}</th>
      <th>{{ trans('web::moons.monthly_quantity') }}</th>
      <th>{{ trans('web::moons.monthly_estimated_value') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach(
      $moon->content->map(function ($type) {
        return $type->materials->map(function ($material) use ($type) {
          // composite quantity = (moon rate * chunk volume) / composite volume
          // reprocessed quantity = composite quantity * material quantity / 100
          $material->pivot->quantity = intdiv(($type->pivot->rate * Seat\Eveapi\Models\Industry\CorporationIndustryMiningExtraction::BASE_DRILLING_VOLUME * 720) / $type->volume, 100) * $material->pivot->quantity * (setting('reprocessing_yield') ?: 0.80);
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
        <td>{{ number_format($material->sum('pivot.quantity') * $material->first()->price->adjusted_price) }}</td>
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

<h4>{{ trans('web::moons.reactions_candidates') }}</h4>

<table class="table datatable table-striped" id="reactionsCandidates">
  <thead>
    <tr>
      <th>{{ trans_choice('web::seat.type', 1) }}</th>
      <th>{{ trans('web::moons.components') }}</th>
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

