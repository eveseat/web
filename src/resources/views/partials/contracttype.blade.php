<i class="fa @if($row->type == 'item_exchange') fa-exchange @else fa-truck @endif"
   data-toggle="tooltip"
   title="" data-original-title="{{ $row->type }}">
</i>
<i class="fa fa-long-arrow-right"></i>
<i class="fa fa-map-marker" data-toggle="tooltip" title="" data-original-title="{{ $row->endlocation }}"></i>
