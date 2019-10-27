<div class="mb-3">
  <div class="btn-group d-flex">
    <button data-filter-value="outstanding" data-filter-field="status" class="btn btn-primary active dt-filters">
      <i class="fas fa-clock"></i>
      Pending
    </button>
    <button data-filter-value="in_progress" data-filter-field="status" class="btn btn-success active dt-filters">
      <i class="fas fa-play"></i>
      In Progress
    </button>
    <button data-filter-value="deleted|cancelled|failed|reversed|rejected" data-filter-field="status" class="btn btn-danger dt-filters">
      <i class="fas fa-trash"></i>
      Deleted
    </button>
    <button data-filter-value="finished|finished_issuer|finished_contractor" data-filter-field="status" class="btn btn-secondary dt-filters">
      <i class="fas fa-check"></i>
      Completed
    </button>
  </div>
</div>
<div class="mb-3">
  <div class="btn-group d-flex">
    <button data-filter-value="unknown" data-filter-field="type" class="btn btn-light active dt-filters">
      <i class="fas fa-question-circle"></i>
      Unknown
    </button>
    <button data-filter-value="item_exchange" data-filter-field="type" class="btn btn-light active dt-filters">
      <i class="fas fa-exchange-alt"></i>
      Item Exchange
    </button>
    <button data-filter-value="auction" data-filter-field="type" class="btn btn-light active dt-filters">
      <i class="fas fa-gavel"></i>
      Auction
    </button>
    <button data-filter-value="courier" data-filter-field="type" class="btn btn-light active dt-filters">
      <i class="fas fa-box"></i>
      Courier
    </button>
    <button data-filter-value="loan" data-filter-field="type" class="btn btn-light active dt-filters">
      <i class="fas fa-handshake"></i>
      Loan
    </button>
  </div>
</div>

@push('javascript')
  <script>
    $(document).ready(function () {
      $('.dt-filters')
          .on('click', function () {
              $(this).hasClass('active') ? $(this).removeClass('active') : $(this).addClass('active');
              window.LaravelDataTables['dataTableBuilder'].ajax.reload();
          });
    });
  </script>
@endpush