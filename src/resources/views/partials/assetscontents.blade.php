@foreach($contents as $asset_content)

  <tr class="hidding">
    <td>{{ $asset_content->quantity }}</td>
    <td>
      {!! img('type', $asset_content->type->typeID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
      {{ $asset_content->type->typeName }}
    </td>
    <td>{{ number_metric($asset_content->quantity * $asset_content->volume) }} m&sup3;</td>
    <td>{{ $asset_content->type->group->groupName  }}</td>
    <td>{{ $asset_content->location_flag }}</td>
  </tr>

@endforeach
