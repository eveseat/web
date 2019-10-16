<a href="#" data-toggle="modal" data-target="#mining-detail"
   data-url="{{ isset($row->character_id) ?
   route('character.view.mining_ledger.details', ['character_id' => $row->character_id, 'date' => $row->date, 'solar_system_id' => $row->solar_system_id, 'type_id' => $row->type_id]) :
   route('corporation.view.mining_ledger.details', ['corporation_id' => $row->corporation_id, 'date' => $row->date, 'solar_system_id', $row->solar_system_id, 'type_id' => $row->type_id]) }}">
  <i class="fas fa-gem"></i>
</a>