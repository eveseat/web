<div class="mb-3">
  <div class="btn-group d-flex">
    <button data-filter-field="standing" data-filter-value="-10" class="btn btn-danger dt-filters active">
      <i class="fas fa-minus"></i>
    </button>
    <button data-filter-field="standing" data-filter-value="-5" class="btn btn-warning dt-filters active">
      <i class="fas fa-minus"></i>
    </button>
    <button data-filter-field="standing" data-filter-value="0" class="btn btn-secondary dt-filters active">
      <i class="fas fa-equals"></i>
    </button>
    <button data-filter-field="standing" data-filter-value="5" class="btn btn-info dt-filters active">
      <i class="fas fa-plus"></i>
    </button>
    <button data-filter-field="standing" data-filter-value="10" class="btn btn-primary dt-filters active">
      <i class="fas fa-plus"></i>
    </button>
  </div>
</div>
<div class="mb-3">
  <div class="btn-group d-flex">
    <button data-filter-field="category" data-filter-value="character" class="btn btn-light dt-filters active">
      <i class="fas fa-user"></i>
      Character
    </button>
    <button data-filter-field="category" data-filter-value="corporation" class="btn btn-light dt-filters active">
      <i class="fas fa-building"></i>
      Corporation
    </button>
    <button data-filter-field="category" data-filter-value="alliance" class="btn btn-light dt-filters active">
      <i class="fas fa-star"></i>
      Alliance
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