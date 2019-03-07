@if($row->type == 'item_exchange' && $row->volume > 0)

  <a href="#" class="contract-item" data-toggle="modal" data-target="#contractsItemsModal"
     data-url="{{ isset($row->character_id) ?
     route('character.view.contracts.items', ['character_id' => $row->character_id, 'contract_id' => $row->contract_id]) :
     route('corporation.view.contracts.items', ['corporation_id' => $row->corporation_id, 'contract_id' => $row->contract_id])}}">
    <i class="fa fa-cubes"></i>
  </a>

@endif
