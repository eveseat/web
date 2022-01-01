<script type="text/javascript">
  $(document).ready(function () {
    //
    // Dynamics UI related to permission management (counters, colors, etc...)
    //
    $('#nav-permissions .badge').text($('.permissions-tab input[type="checkbox"]').filter(':checked').length);

    $('#permissions-accordion a').each(function (index, element) {
        $(this).find('.active-permissions-counter b').text($(element.hash).find('input[type="checkbox"]').filter(':checked').length);
    });

    $('.permissions-tab input[type="checkbox"]').on('change', function () {
      var checked_permissions = $(this).closest('.permissions-tab').find('input[type="checkbox"]').filter(':checked').length;
      $($(this).data('target')).text(checked_permissions);
      $(this).closest('.permissions-tab').find('.panel-heading .active-permissions-counter b').text(checked_permissions);
      $('#nav-permissions .badge').text($('.permissions-tab input[type="checkbox"]').filter(':checked').length);

      // reset filters if permission is removed

      var filter_input = $(this).parent().find('input[type="hidden"]');

      if (filter_input.length > 0) {
        if (! $(this).is(':checked')) {
            filter_input.val('{}').trigger('change');
            $(this).closest('.form-check').find('button').attr('disabled', 'disabled');
        } else {
            $(this).closest('.form-check').find('button').removeAttr('disabled');
        }
      }
    });

    $('.permissions-tab input[type="hidden"]').on('change', function () {
      var button = $(this).closest('div.form-check').find('button');

      if ($(this).val() !== '{}' && !button.hasClass('btn-warning')) {
        button.addClass('btn-warning').removeClass('btn-default');
      }

      if ($(this).val() === '{}' && !button.hasClass('btn-default')) {
        button.addClass('btn-default').removeClass('btn-warning');
      }
    });

    $('.check-all-permissions').on('click', function () {
      var inputs = $($(this).data('target')).find('input[type="checkbox"]');
      inputs.prop('checked', inputs.length !== inputs.filter(':checked').length).trigger('change');
      //$(this).closest('.panel-title').find('.active-permissions-counter b').text(inputs.filter(':checked').length);
      //$('#nav-permissions .badge').text($('.permissions-tab input[type="checkbox"]').filter(':checked').length);
    });

    //
    // Filters entity lookup
    //
    $('#permission-entity-lookup').select2({
      placeholder: '{{ trans('web::seat.select_item_add') }}',
      ajax: {
        url: '{{ route('seatcore::fastlookup.entities') }}',
        dataType: 'json',
        cache: true,
        processResults: function (data, params) {
          return {
            results: data.results
          };
        }
      },
      minimumInputLength: 3
    }).on('select2:select', function (e) {
      var entity = e.params.data;
      var row = $('<tr><td></td><td></td><td><button type="button" class="btn btn-xs btn-danger">remove</button></td></tr>');
      var img = entity.img + ' ' + entity.text;

      row.find('td:eq(0)').text(entity.type);
      row.find('td:eq(1)').html(img);
      row.data('entityid', entity.id);
      row.data('entityname', entity.text);
      row.data('entitytype', entity.type);

      $('#permission-modal table tbody').append(row);

      $('#permission-entity-lookup').val(null).trigger('change');
    });

    //
    // Permission filter modal
    //
    $('#permission-modal')
      .on('show.bs.modal', function (e) {
        $(e.target).data('permission', $(e.relatedTarget).data('permission'));

        filters = JSON.parse($('input[name="filters[' + $(e.relatedTarget).data('permission') + ']"]').val());

        var table = $(e.target).find('table tbody');
        table.empty();

        $.each(filters, function (type, ids) {
          $.each(ids, function (index, entity) {
            var entity_type = type.charAt(0).toUpperCase() + type.slice(1);
            var row = $('<tr><td></td><td></td><td><button type="button" class="btn btn-xs btn-danger">remove</button></td></tr>');
            var img_src = '//image.eveonline.com/' + entity_type + '/' + entity.id + '_64';

            if (type === 'character')
                img_src += '.jpg';
            else
                img_src += '.png';

            var img = '<img class="img-circle eve-icon small-icon" src="' + img_src + '" alt="' + entity.text + '" /> ' + entity.text;

            row.find('td:eq(0)').text(type);
            row.find('td:eq(1)').html(img);
            row.data('entityid', entity.id);
            row.data('entitytype', type);
            row.data('entityname', entity.text);

            table.append(row);
          });
        });
      })
      .on('hide.bs.modal', function (e) {
        var filters_json = {};

        $('#permission-modal table tbody tr').each(function (index, element) {
          var entity_type = $(element).data('entitytype').toLowerCase();

          if (! filters_json.hasOwnProperty(entity_type))
            filters_json[entity_type] = [];

          filters_json[entity_type].push({
            'id': $(element).data('entityid'),
            'text': $(element).data('entityname')
          });
        });

        var filters_input = $('input[name="filters[' + $(e.target).data('permission') + ']"]');
        var filters_value = JSON.stringify(filters_json);

        if (filters_input.val() !== filters_value)
          filters_input.val(filters_value).trigger('change');
      });

    $(document).on('click', '#permission-modal table tbody tr td button.btn-danger', function () {
      $(this).closest('tr').remove();
    });
  });
</script>