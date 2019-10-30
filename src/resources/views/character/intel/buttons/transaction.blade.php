<span class="float-right">
  <button class="btn btn-sm btn-link" data-toggle="modal" data-target="#transactionContentModal"
     data-url="{{ route('character.view.intel.summary.transactions.details', ['character_id' => request()->character_id, 'client_id' => $row->client_id]) }}">
    <i class="fa fa-search-plus"></i>
  </button>
</span>
