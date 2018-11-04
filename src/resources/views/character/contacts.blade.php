@extends('web::character.layouts.view', ['viewname' => 'contacts'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.contacts'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.contacts'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans('web::seat.contacts') }}
        @if(auth()->user()->has('character.jobs'))
          <span class="pull-right">
            <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.contacts']) }}"
               style="color: #000000">
              <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_contacts') }}"></i>
            </a>
          </span>
        @endif
      </h3>
    </div>
    <div class="panel-body">

      <table id="contact-table" class="table compact table-condensed table-hover table-responsive">
        <thead>
        <tr>
          <th>{{ trans_choice('web::seat.name', 1) }}</th>
          <th>{{ trans_choice('web::seat.type', 1) }}</th>
          <th>{{ trans('web::seat.standings') }}</th>
          <th>{{ trans('web::seat.labels') }}</th>
          <th></th>
        </tr>
        </thead>
      </table>
    </div>
  </div>

@stop

@push('javascript')

  <script type="text/javascript">

    $('#contact-table').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        url: "{{url()->current()}}"
      },
      columns: [
        {data: 'name', name: 'resolved_ids.name', sortable: false},
        {data: 'contact_type', name: 'contact_type'},
        {data: 'standing_view', name: 'standing', searchable: false},
        {data: 'label_ids', name: 'label_ids'},
        {data: 'links', name: 'links', sortable: false, searchable: false},
      ],
      createdRow: function( row, data ) {
        if ( data.standing > 0 ) {
          $(row).addClass('success')
        }

        if ( data.standing < 0 ) {
          $(row).addClass('danger')
        }
      },
      drawCallback : function () {
        $("img").unveil(100);
        ids_to_names();
      },
    });

  </script>

@endpush
