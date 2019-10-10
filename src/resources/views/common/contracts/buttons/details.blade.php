<a href="#" data-widget="modal" data-target="#contract-detail"
   data-url="{{ isset($row->character_id) ?
   route('character.view.contracts.items', ['character_id' => $row->character_id, 'contract_id' => $row->contract_id]) :
   route('corporation.view.contracts.items', ['corporation_id' => $row->corporation_id, 'contract_id' => $row->contract_id])}}">
  <i class="fa fa-cubes"></i>
</a>