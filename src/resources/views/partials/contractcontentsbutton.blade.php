@if($row->type == 'ItemExchange')

  <a href="#" class="contract-item" data-toggle="modal" data-target="#contractsItemsModal" a-contract-id="{{ $row->contractID }}">
    <i class="fa fa-cubes"></i>
  </a>

@endif


