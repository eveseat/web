<button data-toggle="modal" data-target="#project-detail" class="btn btn-sm btn-link"
   data-url="{{ route('seatcore::corporation.view.projects.details', ['corporation' => $row->corporation_id, 'project_id' => $row->id]) }}">
  <i class="fa fa-wrench"></i>
</button>