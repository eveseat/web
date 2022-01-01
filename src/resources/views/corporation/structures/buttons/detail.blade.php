<button data-toggle="modal" data-target="#fitting-detail" class="btn btn-sm btn-link"
   data-url="{{ route('seatcore::corporation.view.structures.show', ['corporation' => $row->corporation_id, 'structure_id' => $row->structure_id]) }}">
  <i class="fa fa-wrench"></i>
</button>