<a href="#" data-toggle="modal" data-target="#contract-detail"
   data-url="{{ isset($row->character_id) ?
   route('character.view.contracts.items', ['character' => $row->character, 'contract_id' => $row->contract_id]) :
   route('corporation.view.contracts.items', ['corporation' => $row->corporation_id, 'contract_id' => $row->contract_id])}}">
  <i class="fa fa-cubes"></i>
</a>