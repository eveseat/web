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
      $('table#keys-table').DataTable({
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
          {data: 'characters', name: 'characters', orderable: false},
          {data: 'actions', name: 'actions', orderable: false}
        ],
        dom             : '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>>rt<"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-6"l><"col-sm-6"f>>',
        "fnDrawCallback": function () {
          $(document).ready(function () {
            $("img").unveil(100);
          });
        }
      });
    });

  </script>
@endpush
