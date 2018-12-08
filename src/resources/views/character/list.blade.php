@extends('web::layouts.grids.12')

@section('title', trans_choice('web::seat.character', 2))
@section('page_header', trans_choice('web::seat.character', 2))

@section('full')



  <div class="col-md-12">
    <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#" data-toggle="tab" data-all="true">{{ trans_choice('web::seat.character', 2) }}</a></li>
        @if(auth()->user()->has('character.list', false))
          <li><a href="#" data-toggle="tab" data-all="false">SeAT Group only</a></li>
        @endif
      </ul>
      <div class="tab-content">
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
      <!-- /.tab-content -->
    </div>
    <!-- nav-tabs-custom -->
  </div>

@stop

@push('javascript')

  <script>

    $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
      character_list.draw();
    });

    function filtered() {
      return !$("div.nav-tabs-custom > ul > li.active > a").data('all');
    }

    var character_list = $('table#character-list').DataTable({
      processing      : true,
      serverSide      : true,
      ajax            : {
        url : '{{ route('character.list.data') }}',
        data: function ( d ) {
          d.filtered = filtered();
        }
      },
      columns         : [
        {data: 'name_view', name: 'name'},
        {data: 'corporation_id', name: 'corporation_id'},
        {data: 'alliance_id', name: 'alliance_id'},
        {data: 'security_status', name: 'security_status'},
        {data: 'actions', name: 'actions', searchable: false, orderable: false}
      ],
      drawCallback: function () {
        $("img").unveil(100);

        ids_to_names();
      },
    });

  </script>

  @include('web::includes.javascript.id-to-name')

@endpush
