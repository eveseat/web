<table class="table table-condensed table-hover table-responsive">
  <thead>
  <tr>
    <th>{{ trans('web::seat.quantity') }}</th>
    <th>{{ trans_choice('web::seat.type', 1) }}</th>
    <th>{{ trans('web::seat.volume') }}</th>
    <th>{{ trans_choice('web::seat.group',1) }}</th>
  </tr>
  </thead>
  <tbody>

  @foreach($assets as $asset)

    <tr>
      <td>{{ $asset->quantity }}</td>
      <td>
        {!! img('type', $asset->typeID, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
        {{ $asset->typeName }}
      </td>
      <td>{{ number_metric($asset->quantity * $asset->volume) }} m&sup3;</td>
      <td>{{ $asset->groupName }}</td>
    </tr>

  @endforeach

  </tbody>
</table>
