<div data-type="rule" class="col-12 d-none" id="rule-template">
  <div class="card">
    <div class="card-status-start bg-info"></div>
    <div class="card-header">
      <div class="card-actions btn-actions">
        <button data-dismiss="rule" class="btn-action" type="button">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-3">
          <label class="form-label">Type</label>
          <select class="form-control rule-type">
            @foreach($filters as $filter)
              <option value="{{ $filter->name }}" data-src="{{ is_array($filter->src) ? json_encode($filter->src) : $filter->src }}" data-path="{{ $filter->path }}" data-field="{{ $filter->field }}">{{ $filter->label }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-3">
          <label class="form-label">Operator</label>
          <select class="form-control rule-operator">
            <option value="=">Is</option>
            <option value="<>">Is Not</option>
            <option value=">">Is Greater Than</option>
            <option value="<">Is Less Than</option>
            <option value="contains">Contains</option>
          </select>
        </div>
        <div class="col-6">
          <label class="form-label">Criteria</label>
          <select class="form-control rule-criteria" style="width: 100%;"></select>
        </div>
      </div>
    </div>
  </div>
</div>