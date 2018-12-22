@if($row->type == 'item_exchange' && $row->volume > 0)

  <a href="#" class="contract-item" data-toggle="modal" data-target="#contractsItemsModal"
     data-url="{{ $url }}">
    <i class="fa fa-cubes"></i>
  </a>

@endif
