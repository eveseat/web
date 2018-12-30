<i class="fas @if($row->type == 'item_exchange') fa-exchange-alt @else fa-truck @endif" data-toggle="tooltip" title="{{ ucwords(str_replace('_', ' ', $row->type)) }}"></i>
<i class="fas fa-long-arrow-alt-right"></i>
<i class="fas fa-map-marker-alt" data-toggle="tooltip" title="{{ $row->endlocation }}"></i>
