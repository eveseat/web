<span class="float-right">
  <button class="btn btn-sm btn-link" data-toggle="modal" data-target="#transactionContentModal"
     data-url="{{ route('seatcore::character.view.intel.summary.transactions.details', ['character' => request()->character, 'client_id' => $row->client_id]) }}">
    <i class="fa fa-search-plus"></i>
  </button>
</span>
