<h4>Location</h4>

<div class="row">
  <div class="col-4">
    <dl>
      <dt>Solar System</dt>
      <dd>
        @if($asset->location_flag == 'AssetSafety' || $asset->location_type == 'station')
          @include('web::partials.system', ['system' => $asset->station->solar_system->name, 'security' => $asset->station->solar_system->security])
        @else
          @include('web::partials.system', ['system' => $asset->container->container->solar_system->name, 'security' => $asset->container->container->solar_system->security])
        @endif
      </dd>
    </dl>
  </div>
  <div class="col-4">
    <dl>
      <dt>Structure</dt>
      <dd>{{ ($asset->location_flag == 'AssetSafety' || $asset->location_type == 'station') ? $asset->station->name : $asset->container->container->name }}</dd>
    </dl>
  </div>
  <div class="col-4">
    <dl>
      <dt>Container</dt>
      <dd>{{ $asset->location_flag == 'AssetSafety' ? 'Asset Safety' : $asset->name }}</dd>
    </dl>
  </div>
</div>

<h4>Content</h4>

<table class="table table-sm table-striped">
  <thead>
    <tr>
      <th>Type</th>
      <th>Quantity</th>
      <th>Volume</th>
      <th>Group</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    @foreach($asset->content as $item)
      <tr>
        <td>
          @include('web::partials.type', [
            'type_id' => $item->type->typeID,
            'type_name' => $item->name ? sprintf('%s (%s)', $item->name, $item->type->typeName) : $item->type->typeName,
            'variation' => $item->type->group->categoryID == 9 ? 'bpc' : 'icon',
          ])
        </td>
        <td>{{ number($item->quantity, 0) }}</td>
        <td>{{ number_metric($item->quantity * $item->type->volume) }} m&sup3</td>
        <td>{{ $item->type->group->groupName }}</td>
        <td>
          @if($item->content->isNotEmpty())
            @if(in_array($item->type->group->categoryID, [6, 65]))
              @include('web::common.assets.buttons.fitting', ['row' => $item])
            @else
              @include('web::common.assets.buttons.cargo', ['row' => $item])
            @endif
          @endif
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
