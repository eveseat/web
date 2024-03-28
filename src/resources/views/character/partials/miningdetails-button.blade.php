<a href="#" class="btn btn-sm btn-link float-right"
   data-type-id="{{$row->type_id}}"
   data-date="{{ $row->date }}"
   data-system-name="{{ $row->solar_system->name }}"
   data-type-name="{{ $row->type->typeName }}"
   data-url="{{ route('seatcore::character.view.detailed_mining_ledger', [$row->character_id, $row->date, $row->solar_system_id, $row->type_id]) }}"
   data-widget="modal" data-target="#detailed-ledger">
  <i class="fa fa-cubes"></i>
</a>