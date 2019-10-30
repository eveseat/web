<span class="float-right">
  <button class="btn btn-sm btn-link" data-toggle="modal" data-target="#topMailContentModal"
     data-url="{{ route('character.view.intel.summary.mail.details', ['character_id' => request()->character_id, 'from' => $row->from]) }}">
    <i class="fa fa-search-plus"></i>
  </button>
</span>
