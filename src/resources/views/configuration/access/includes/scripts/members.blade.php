<script type="text/javascript">
  $(document).ready(function() {
    $('#member-entity-lookup').select2({
        placeholder: '{{ trans('web::seat.select_item_add') }}',
        ajax: {
            url: '{{ route('fastlookup.groups') }}',
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

            for (var i = 0; i < group.text.length; i++) {
                html += '<span data-id="' + group.character_id[i] +'">' + group.img[i] + ' ' + group.text[i] + '</span> ';
            }

            return $(html);
        },
        minimumInputLength: 3
    }).on('select2:select', function (e) {
        var entity = e.params.data, character;
        var url = '{{ route('character.view.sheet', ['character_id' => '']) }}';
        var row = $('<tr><td></td><td></td><td><button type="button" class="btn btn-xs btn-danger pull-right">remove</button></td></tr>');

        character = $('<a></a>');
        character.attr('href', url + '/' + entity.character_id[0]);
        character.attr('data-entityid', entity.character_id[0]);
        character.html(entity.img[0] + ' ' + entity.text[0]);

        row.attr('data-groupid', entity.id);
        row.find('td:eq(0)').append(character);

        for (var i = 1; i < entity.text.length; i++) {
            character = $('<a></a>');
            character.attr('href', url + '/' + entity.character_id[i]);
            character.attr('data-entityid', entity.character_id[i]);
            character.html(entity.img[i] + ' ' + entity.text[i]);

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
      var group_id = button.data('groupid');
      var row = button.closest('tr');
      var url = '{{ route('configuration.access.roles.edit.remove.group', ['role_id' => $role->id, 'group_id' => '']) }}';

      button.attr('disabled', 'disabled');
      row.addClass('danger');

      $.ajax({
        url: url + '/' + group_id,
        method: 'DELETE'
      }).done(function () {
        row.remove();
        $('#nav-members a span.badge').text($('#tab-members table tbody tr').length)
      }).fail(function () {
        console.error('An error occured while attempting to remove the user.');
        button.removeAttr('disabled');
        row.removeClass('danger');
      });
    });
  });
</script>