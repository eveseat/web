<div class="modal fade in show" tabindex="-1" role="dialog" id="filters" aria-modal="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h4 class="modal-title">Filters</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card" data-type="ruleset">
          <div class="card-header">
            <div class="form-inline">
              <div class="form-group">
                <label class="mr-2">Match Kind</label>
                <select class="form-control match-kind">
                  <option value="and">All</option>
                  <option value="or">Any</option>
                </select>
              </div>
            </div>
          </div>
          <div class="card-body pb-0"></div>
          <div class="card-footer">
            <div class="btn-group d-flex">
              <button class="btn btn-light btn-rule">
                <i class="fas fa-filter"></i> Add Rule
              </button>
              <button class="btn btn-light btn-ruleset">
                <i class="fas fa-clone"></i> Add Group
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer bg-warning">
        <div class="btn-group">
          <button type="button" data-dismiss="modal" class="btn btn-danger">
            <i class="fas fa-times"></i> Cancel
          </button>
          <button type="button" class="btn btn-success">
            <i class="fas fa-check"></i> Update
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- JS templates --}}
@include('web::components.filters.modals.filters.rule')
@include('web::components.filters.modals.filters.ruleset')

@push('javascript')
  <script>
    function buildFilters(source)
    {
        var filters = {};

        source.each(function (i, ruleset) {
            var match = $(ruleset).find('.match-kind').first();
            filters[match.val()] = [];

            $(ruleset).find('> .card-body > [data-type="rule"], > .card-body > [data-type="ruleset"]').each(function (j, rule) {
                switch ($(rule).data('type')) {
                    case 'rule':
                        filters[match.val()].push({
                            path: $('option:selected', $(rule).find('.rule-type')).data('path'),
                            field: $('option:selected', $(rule).find('.rule-type')).data('field'),
                            operator: $(rule).find('.rule-operator').first().val(),
                            criteria: $(rule).find('.rule-criteria').first().val()
                        });
                        break;
                    case 'ruleset':
                        filters[match.val()].push(buildFilters($(rule)));
                        break;
                }
            });
        });

        return filters;
    }

    $('body')
      .on('click', '.btn-rule', function (e) {
        var rule = $('#rule-template').clone();
        rule.removeAttr('id');
        rule.removeClass('d-none');

        rule.find('.rule-criteria').select2({
            ajax: {
                url: function () {
                    return $('option:selected', $(this).closest('.form-row').find('.rule-type')).data('src');
                }
            }});

        $(e.target).closest('.card').find('.card-body').first().append(rule);
      })
      .on('click', '.btn-ruleset', function (e) {
        var group = $('#ruleset-template').clone();
        group.removeAttr('id');
        group.removeClass('d-none');

        $(e.target).closest('.card').find('.card-body').first().append(group);
      })
      .on('click', 'button[data-dismiss="callout"]', function (e) {
        $(e.target).closest('.callout').remove();
      })
      .on('click', 'button[data-dismiss="card"]', function (e) {
        $(e.target).closest('.card').remove();
      })
      .on('click', '#filters .btn-success', function () {
          console.debug(JSON.stringify(buildFilters($('#filters .modal-body').children('[data-type="ruleset"]'))));
      });
  </script>
@endpush