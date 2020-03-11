<span class="float-right">
  <button class="btn btn-sm btn-link" data-toggle="modal" data-target="#journalContentModal"
     data-url="{{ route('character.view.intel.summary.journal.details', ['character_id' => request()->character_id, 'first_party_id' => $row->first_party_id, 'second_party_id' => $row->second_party_id, 'ref_type' => $row->ref_type]) }}">
    <i class="fa fa-search-plus"></i>
  </button>
</span>
