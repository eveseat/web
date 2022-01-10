<span class="float-right">
  <button class="btn btn-sm btn-link" data-bs-toggle="modal" data-bs-target="#transactionContentModal"
     data-url="{{ route('seatcore::character.view.intel.summary.transactions.details', ['character' => request()->character, 'client_id' => $row->client_id]) }}">
    <i class="fa fa-search-plus"></i>
  </button>
</span>
