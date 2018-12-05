@extends('web::layouts.grids.12')

@section('title', trans_choice('web::seat.character', 2))
@section('page_header', trans_choice('web::seat.character', 2))

@section('full')



  <div class="col-md-12">
    <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab"
                              aria-expanded="true">{{ trans_choice('web::seat.character', 2) }}</a></li>
        @if(auth()->user()->has('character.list',false))
          <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">SeAT Group only</a></li>
        @endif
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">

          <table class="table compact table-condensed table-hover table-responsive"
                 id="all-character-list" data-page-length=100>
            <thead>
            <tr>
              <th>{{ trans_choice('web::seat.name', 1) }}</th>
              <th>{{ trans_choice('web::seat.corporation', 1) }}</th>
              <th>{{ trans('web::seat.alliance') }}</th>
              <th>{{ trans('web::seat.security_status') }}</th>
              <th></th>
            </tr>
            </thead>
          </table>

        </div>
      @if(auth()->user()->has('character.list',false))
        <!-- /.tab-pane -->
          <div class="tab-pane" id="tab_2">
            <table class="table compact table-condensed table-hover table-responsive"
                   id="character-list" data-page-length=100>
              <thead>
              <tr>
                <th>{{ trans_choice('web::seat.name', 1) }}</th>
                <th>{{ trans_choice('web::seat.corporation', 1) }}</th>
                <th>{{ trans('web::seat.alliance') }}</th>
                <th>{{ trans('web::seat.security_status') }}</th>
                <th></th>
              </tr>
              </thead>
            </table>


          </div>
      @endif
      <!-- /.tab-pane -->
      </div>
      <!-- /.tab-content -->
    </div>
    <!-- nav-tabs-custom -->
  </div>



@stop

@push('javascript')

  <script>

    $(function () {
      $('table#all-character-list').DataTable({
        processing      : true,
        serverSide      : true,
        ajax            : {
          'url' : '{{ route('character.list.data') }}',
          'type': 'POST',
          'data': {
            'filtered': false
          }
        },
        columns         : [
          {data: 'name', name: 'name'},
          {data: 'corporation_id', name: 'corporation_id'},
          {data: 'alliance_id', name: 'alliance_id'},
          {data: 'security_status', name: 'security_status'},
          {data: 'actions', name: 'actions'}
        ],
        dom             : '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>>rt<"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-6"l><"col-sm-6"f>>',
        "fnDrawCallback": function () {
          $(document).ready(function () {
            $("img").unveil(100);

            ids_to_names();
          });
        },
        order           : [[0, "asc"]]
      });
      $('table#character-list').DataTable({
        processing      : true,
        serverSide      : true,
        ajax            : {
          'url' : '{{ route('character.list.data') }}',
          'type': 'POST',
          'data': {
            'filtered': true
          }
        },
        columns         : [
          {data: 'name', name: 'name'},
          {data: 'corporation_id', name: 'corporation_id'},
          {data: 'alliance_id', name: 'alliance_id'},
          {data: 'security_status', name: 'security_status'},
          {data: 'actions', name: 'actions'}
        ],
        dom             : '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>>rt<"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-6"l><"col-sm-6"f>>',
        "fnDrawCallback": function () {
          $(document).ready(function () {
            $("img").unveil(100);

            ids_to_names();
          });
        },
        order           : [[0, "asc"]]
      });
    });

  </script>

  @include('web::includes.javascript.id-to-name')

@endpush
