@foreach($contents as $asset_content)

  <tr class="hidding">
    <td>{{ $asset_content->quantity }}</td>
    <td>
      {!! img('type', $asset_content->typeID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
      {{ $asset_content->typeName }}
    </td>
    <td>{{ number_metric($asset_content->quantity * $asset_content->volume) }} m&sup3;</td>
    <td>{{ $asset_content->groupName }}</td>
  </tr>

@endforeach
