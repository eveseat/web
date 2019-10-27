<div class="mb-3">
  <div class="btn-group d-flex">
    <button data-filter-value="pending" data-filter-field="status" class="btn btn-primary active dt-filters">
      <i class="fas fa-play"></i>
      Pending
    </button>
    <button data-filter-value="expired" data-filter-field="status" class="btn btn-danger dt-filters">
      <i class="fas fa-history"></i>
      Expired
    </button>
    <button data-filter-value="completed" data-filter-field="status" class="btn btn-secondary dt-filters">
      <i class="fas fa-check"></i>
      Completed
    </button>
  </div>
</div>
<div class="mb-3">
  <div class="btn-group d-flex">
    <button data-filter-value="0" data-filter-field="type" class="btn btn-light active dt-filters">
      <i class="fas fa-caret-up"></i>
      Sell Orders
    </button>
    <button data-filter-value="1" data-filter-field="type" class="btn btn-light active dt-filters">
      <i class="fas fa-caret-down"></i>
      Buy Orders
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