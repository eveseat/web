<div class="mb-3">
  <div class="btn-group d-flex">
    <button type="button"  data-filter-field="type" data-filter-value="bpo" class="btn btn-primary dt-filters active">
      <i class="fas fa-file"></i>
      Original
    </button>
    <button type="button" data-filter-field="type" data-filter-value="bpc" class="btn btn-info dt-filters active">
      <i class="fas fa-copy"></i>
      Copy
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