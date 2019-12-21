{{-- <button data-toggle="modal" data-target="#filters-modal" class="btn btn-warning" data-filters="{{ json_encode($rules ?? '{"and":[]}') }}"> --}}
<button data-toggle="modal" data-target="#filters-modal" class="btn btn-warning" id="filters-btn" data-filters="{{ $rules ? json_encode($rules) : '{}' }}">
  <i class="fas fa-sliders-h"></i> Filters
</button>