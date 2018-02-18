@if($row->type == 'item_exchange')

  <a href="#" class="contract-item" data-toggle="modal" data-target="#contractsItemsModal"
     a-contract-id="{{ $row->contract_id }}">
    <i class="fa fa-cubes"></i>
  </a>

@endif
