<div class="mb-3">
  <div class="btn-group d-flex">
    <button type="button" data-filter-field="status" data-filter-value="active" class="btn btn-primary dt-filters active">
      <i class="fas fa-play"></i>
      Running
    </button>
    <button type="button" data-filter-field="status" data-filter-value="paused" class="btn btn-warning dt-filters">
      <i class="fas fa-pause"></i>
      Paused
    </button>
    <button type="button" data-filter-field="status" data-filter-value="ready" class="btn btn-success dt-filters">
      <i class="fas fa-check"></i>
      Completed
    </button>
    <button type="button" data-filter-field="status" data-filter-value="cancelled" class="btn btn-danger dt-filters">
      <i class="fas fa-ban"></i>
      Cancelled
    </button>
    <button type="button" data-filter-field="status" data-filter-value="delivered" class="btn btn-secondary dt-filters">
      <i class="fas fa-history"></i>
      Delivered
    </button>
  </div>
</div>
<div class="mb-3">
  <div class="btn-group d-flex">
    <button type="button" data-filter-field="activity" data-filter-value="1" class="btn btn-light dt-filters active">
      <i class="fas fa-industry"></i>
      Manufacturing
    </button>
    <button type="button" data-filter-field="activity" data-filter-value="3" class="btn btn-light dt-filters active">
      <i class="fas fa-hourglass-half"></i>
      Research TE
    </button>
    <button type="button" data-filter-field="activity" data-filter-value="4" class="btn btn-light dt-filters active">
      <i class="fas fa-gem"></i>
      Research ME
    </button>
    <button type="button" data-filter-field="activity" data-filter-value="5" class="btn btn-light dt-filters active">
      <i class="fas fa-flask"></i>
      Copying
    </button>
    <button type="button" data-filter-field="activity" data-filter-value="8" class="btn btn-light dt-filters active">
      <i class="fas fa-microscope"></i>
      Invention
    </button>
    <button type="button" data-filter-field="activity" data-filter-value="11" class="btn btn-light dt-filters active">
      <i class="fas fa-atom"></i>
      Reaction
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