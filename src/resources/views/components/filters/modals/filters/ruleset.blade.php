<div data-type="ruleset" class="col-12 d-none" id="ruleset-template">
  <div class="card">
    <div class="card-status-start bg-success"></div>
    <div class="card-header">
      <div class="row row-cols-auto g-3 align-items-center">
        <div class="col-12">
          <div class="input-group">
            <div class="input-group-text">Match Kind</div>
            <select class="form-select match-kind">
              <option value="and">All</option>
              <option value="or">Any</option>
            </select>
          </div>
        </div>
      </div>
      <div class="card-actions btn-actions">
        <button data-dismiss="ruleset" class="btn-action">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="row row-cards"></div>
    </div>
    <div class="card-footer">
      <div class="btn-group d-flex">
        <button class="btn btn-light btn-rule">
          <i class="fas fa-filter"></i> Add Rule
        </button>
      </div>
    </div>
  </div>
</div>