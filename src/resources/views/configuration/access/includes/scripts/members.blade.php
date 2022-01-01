<script type="text/javascript">
  $(document).ready(function() {
    $('#member-entity-lookup')
      .select2({
        placeholder: '{{ trans('web::seat.select_item_add') }}',
        ajax: {
            url: '{{ route('seatcore::fastlookup.users') }}',
            dataType: 'json',
            cache: true,
            processResults: function (data, params) {
                return {
                    results: data.results
                };
            }
        },
        templateResult: function(group) {
            var html = '';

            if (! group.type)
                return group.text;

            html += '<span data-id="' + group.character_id + '">' + group.img[0] + ' ' + group.text + '</span> ';

            for (var i = 1; i < group.img.length; i++) {
                html += group.img[i] + ' ';
            }

            return $(html);
        },
        minimumInputLength: 3
      })
      .on('select2:select', function (e) {
        var entity = e.params.data, character;
        var row = $('<tr><td></td><td></td><td><button type="button" class="btn btn-xs btn-danger float-right"><i class="fas fa-trash"></i> Remove</button></td></tr>');

        character = $('<a>');
        character.attr({
            href: entity.href,
            target: '_blank',
            'data-entityid': entity.character_id
        });
        character.html(entity.img[0] + ' ' + entity.text);

        row.attr('data-groupid', entity.id);
        row.find('td:eq(0)').append(character);

        for (var i = 1; i < entity.img.length; i++) {
            character = $('<a>');
            character.attr({
                href: $(entity.img[i]).data('link'),
                target: '_blank',
                title: $(entity.img[i]).data('name'),
                'data-toggle': 'tooltip'
            });
            character.append(entity.img[i]);
            character.tooltip();

            row.find('td:eq(1)').append(character).append(' ');
        }

        row.find('button').attr('data-groupid', entity.id);

        $('#member-modal table tbody').append(row);

        $('#member-entity-lookup').val(null).trigger('change');
      });

    $(document).on('click', '#member-modal table tbody tr td button.btn-danger', function () {
        var button = $(this);
        var row = button.closest('tr');

        row.remove();
    });

    $('#member-modal').on('hide.bs.modal', function () {

        var members = {
            'ids': [],
            'rows': []
        };

        $('#member-modal table tbody tr').each(function (index, element) {

            var row = $(element);

            // we only append the member if it does not already exists
            if ($(document).find('#tab-members table tbody tr button[data-groupid="' + row.data('groupid') + '"]').length < 1) {
                members.ids.push(row.data('groupid'));
                row.addClass('table-warning');
                members.rows.push(row);
            }
        });

        $('#tab-members table tbody').append(members.rows);
        $('#tab-members table tfoot input[type="hidden"]').val(JSON.stringify(members.ids));
    });

    //
    // User removal action
    //
    $(document).on('click', '#tab-members table tbody tr td button.btn-danger', function () {
      var button = $(this);
      var row = button.closest('tr');

      if (! button.data('url')) {
          row.remove();
          $('#nav-members a span.badge').text($('#tab-members table tbody tr').length);
          return;
      }

      button.attr('disabled', 'disabled');
      row.addClass('danger');

      $.ajax({
        url: button.data('url'),
        method: 'DELETE'
      }).done(function () {
        row.remove();
        $('#nav-members a span.badge').text($('#tab-members table tbody tr').length);
      }).fail(function () {
        console.error('An error occurred while attempting to remove the user.');
        button.removeAttr('disabled');
        row.removeClass('danger');
      });
    });
  });
</script>