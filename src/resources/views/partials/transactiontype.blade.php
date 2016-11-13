@if($row->transactionType == 'buy')
  Bought
@else
  Sold
@endif
{!! img('type', $row->typeID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
{{ $row->typeName }}
<i class="fa fa-home pull-right" data-toggle="tooltip"
   title="" data-original-title="{{ $row->stationName }}"></i>
