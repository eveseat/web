<div class="callout callout-info d-none" data-type="rule" id="rule-template">
  <button data-bs-dismiss="callout" class="btn-close" type="button"></button>
  <div class="form-row">
    <div class="form-group col-3">
      <label>Type</label>
      <select class="form-control rule-type">
        @foreach($filters as $filter)
          <option value="{{ $filter->name }}" data-src="{{ is_array($filter->src) ? json_encode($filter->src) : $filter->src }}" data-path="{{ $filter->path }}" data-field="{{ $filter->field }}">{{ $filter->label }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group col-2">
      <label>Operator</label>
      <select class="form-control rule-operator">
        <option value="=">Is</option>
        <option value="<>">Is Not</option>
        <option value=">">Is Greater Than</option>
        <option value="<">Is Less Than</option>
        <option value="contains">Contains</option>
      </select>
    </div>
    <div class="form-group col-7">
      <label>Criteria</label>
      <select class="form-control rule-criteria" style="width: 100%;"></select>
    </div>
  </div>
</div>