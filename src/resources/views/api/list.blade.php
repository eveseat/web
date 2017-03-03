@extends('web::layouts.grids.12')

@section('title', trans('web::seat.api_all'))
@section('page_header', trans('web::seat.api_all'))

@section('full')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.api_all') }}
        <span class="pull-right">
          @if (auth()->user()->has('apikey.toggle_status', false))
            <a href="{{ route('api.key.disable.all') }}" class="btn btn-xs btn-warning">
            {{ trans('web::seat.disable_all_enabled') }}
          </a>
          @endif
          <a href="{{ route('api.key.enable.all') }}" class="btn btn-xs btn-primary">
            {{ trans('web::seat.reenable_all_disabled') }}
          </a>
        </span>
      </h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="keys-table" data-page-length=25>
        <thead>
        <tr>
          <th>{{ trans_choice('web::seat.key_id', 1) }}</th>
          <th>{{ trans('web::seat.enabled') }}</th>
          <th>{{ trans_choice('web::seat.type', 1) }}</th>
          <th>{{ trans('web::seat.expiry') }}</th>
          <th>{{ trans_choice('web::seat.tags', 2) }}</th>
          <th>{{ trans_choice('web::seat.character', 1) }}</th>
          <th></th>
        </tr>
        </thead>
      </table>

    </div>
  </div>

@stop

@push('javascript')
<script>

  $(function () {
    var keyTable = $('table#keys-table').DataTable({
      processing      : true,
      serverSide      : true,
      ajax            : '{{ route('api.key.list.data') }}',
      columns         : [
        {data: 'key_id', name: 'key_id'},
        {
          data: 'enabled', name: 'enabled', render: function (data) {
            if (data == 1) return 'Yes';
            if (data == 0) return 'No';
          }
        },
        {
          data: 'info.type', name: 'info.type', render: function (data) {

            if (typeof data === 'undefined') {
              return 'Unknown';
            }

            return data

          }
        },
        {
          data: 'info.expires', name: 'info.expires', render: function (data) {

            if (typeof data === 'undefined') {
              return 'Unknown';
            }

            return data

          }
        },
        {data: 'tags', name: 'tags', orderable: false},
        {data: 'characters', name: 'characters', orderable: false},
        {data: 'actions', name: 'actions', orderable: false},
      ],
      "fnDrawCallback": function () {
        $(document).ready(function () {
          $("img").unveil(100);
        });
      },
      // load tags buffer foreach row
      "fnRowCallback": function(row, data, index){
        var input = $(row).find('input');

        // create the tag buffer if it's relevant
        if (input.data('tags') == null) {
          input.data('tags', []);
        }

        console.debug(data);

        $(row).find('.label').each(function(){
          // remove the last character from span text since it's a separator between the tag and the icon
          input.data('tags').push($(this).text().substr(0, ($(this).text().length - 1)));
        });
      }
    });

    // enable to remove existing tag from an Api Key
    $('body')
      .on('click', '.label[data-role="tag"] .fa.fa-times', function(){
        var row = keyTable.row($(this).closest('tr').get(0).rowIndex-1).data();
        var tag = $(this).closest('.label');
        var input = $(this).closest('td').find('input');

        $.ajax('{{ route('json.key.tags.delete') }}', {
            dataType: 'json',
            method: 'DELETE',
            data: {
                _method: 'DELETE',
                _token: '{{ csrf_token() }}',
                key_id: row.key_id,
                tag_id: tag.attr('data-tag_id')
            },
            success: function(){
                // remove the last character from span text since it's a separator between the tag and the icon
                var tagsIndex = $.inArray(tag.text().substr(0, (tag.text().length - 1)), input.data('tags'));
                if (tagsIndex >= 0) {
                    input.data('tags').splice(tagsIndex, 1);
                }

                tag.remove();
            }
        });
      })
      // enable to edit tags from an Api Key
      .on('click', 'button[data-role="tag-editor"]', function(){
        if ($(this).closest('td').find('input').hasClass('hidden')) {
          $(this).closest('td').find('input').removeClass('hidden');
          $(this).closest('td').find('input').focus();
        }
      })
      // enable to add tags to an Api Key
      .on('input', 'input[data-role="taggable"]', function(){
        var input = $(this);

        // process the event only if the string is containing , character
        if((sepCursor = input.val().indexOf(',')) >= 0) {

          // create the tags buffer for the current field if needed
          if (input.data('tags') == null) {
            input.data('tags', []);
          }

          // remove the title row from indexes since datatable doesn't know it
          var row = keyTable.row($(this).closest('tr').get(0).rowIndex-1).data();
          var tag = input.val().substr(0, sepCursor);

          // check if the string is already known in the tag list. Create it if the value is relevant.
          if ($.inArray(tag, input.data('tags')) < 0) {

            $.ajax('{{ route('json.key.tags.delete') }}', {
              dataType: 'json',
              method: 'POST',
              data: {
                _token: '{{ csrf_token() }}',
                key_id: row.key_id,
                tag: tag
              },
              success: function(data){
                $('<span class="label label-info" data-role="tag" data-tag_id="' + data.tag_id +'">' + tag + ' <i class="fa fa-times" style="cursor: pointer;"></i></span>')
                  .insertBefore(input)
                  .after(' ');

                input.data('tags').push(tag);
              }
            });
          }

          input.val('');
        }
      })
      .on('blur', 'input[data-role="taggable"]', function(){
        $(this).addClass('hidden');
      });
  });
</script>
@endpush
