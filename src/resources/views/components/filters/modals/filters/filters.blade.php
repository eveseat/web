<div class="modal fade in show" tabindex="-1" role="dialog" id="filters-modal" aria-modal="true">
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
                            name: $(rule).find('.rule-type').val(),
                            path: $('option:selected', $(rule).find('.rule-type')).data('path'),
                            field: $('option:selected', $(rule).find('.rule-type')).data('field'),
                            operator: $(rule).find('.rule-operator').val(),
                            criteria: $(rule).find('.rule-criteria').val(),
                            text: $('option:selected', $(rule).find('.rule-criteria')).text()
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
      .on('click', '#filters-modal .btn-success', function () {
          document.getElementById('filters-btn').dataset.filters =
              JSON.stringify(buildFilters($('#filters-modal .modal-body').children('[data-type="ruleset"]')));
          $('#filters-modal').modal('toggle');
      })
      .on('show.bs.modal', '#filters-modal', function (e) {
          if (! e.relatedTarget.dataset.filters || e.relatedTarget.dataset.filters === '{}')
              return;

          var rules = JSON.parse(e.relatedTarget.dataset.filters);
          var modal = $('#filters-modal .modal-body > .card > .card-body');

          modal.empty();

          $('#filters-modal .modal-body > .card > .card-header .match-kind').val(rules.hasOwnProperty('and') ? 'and' : 'or');

          rules = rules.hasOwnProperty('and') ? rules.and : rules.or;

          if (! rules)
              return;

          rules.forEach((rule) => {
              if (rule.hasOwnProperty('name')) {
                  node = $('#rule-template').clone();
                  node.removeAttr('id');
                  node.removeClass('d-none');

                  node.find('.rule-operator').val(rule.operator);

                  type = node.find('.rule-type');
                  criteria = node.find('.rule-criteria');

                  type.val(rule.name);

                  criteria.select2({
                      ajax: {
                          url: function () {
                              return $('option:selected', type).data('src');
                          }
                      }
                  });

                  criteria.append(new Option(rule.text, rule.criteria, true, true)).trigger('change');
                  criteria.trigger({
                      type: 'select2:select',
                      params: {
                          data: {
                              text: rule.text,
                              id: rule.criteria
                          },
                      }
                  });

                  modal.append(node);
              }

              if (rule.hasOwnProperty('and') || rule.hasOwnProperty('or')) {
                  ruleset = $('#ruleset-template').clone();
                  ruleset.removeAttr('id');
                  ruleset.removeClass('d-none');

                  ruleset.find('.match-kind').val(rule.hasOwnProperty('and') ? 'and': 'or');

                  ruleset_rules = rule.hasOwnProperty('and') ? rule.and : rule.or;

                  if (ruleset_rules) {
                      ruleset_rules.forEach((ruleset_rule) => {
                          node = $('#rule-template').clone();
                          node.removeAttr('id');
                          node.removeClass('d-none');

                          node.find('.rule-operator').val(ruleset_rule.operator);

                          type = node.find('.rule-type');
                          criteria = node.find('.rule-criteria');

                          type.val(ruleset_rule.name);

                          criteria.select2({
                              ajax: {
                                  url: function () {
                                      return $('option:selected', type).data('src');
                                  }
                              }
                          });

                          criteria.append(new Option(ruleset_rule.text, ruleset_rule.criteria, true, true)).trigger('change');
                          criteria.trigger({
                              type: 'select2:select',
                              params: {
                                  data: {
                                      text: ruleset_rule.text,
                                      id: ruleset_rule.criteria
                                  },
                              }
                          });

                          ruleset.find('.card-body').append(node);
                      });
                  }

                  modal.append(ruleset);
              }
          });
      });
  </script>
@endpush