@extends('web::character.layouts.view', ['viewname' => 'contacts', 'breadcrumb' => trans('web::seat.contacts')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.contacts'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#" data-toggle="tab" data-characters="single">{{ trans('web::seat.contacts') }}</a></li>
        <li><a href="#" data-toggle="tab" data-characters="all">{{ trans('web::seat.linked_characters') }} {{ trans('web::seat.contacts') }}</a></li>
        @if(auth()->user()->has('character.jobs'))
          <li class="pull-right">
            <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.contacts']) }}"
               style="color: #000000">
              <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_contacts') }}"></i>
            </a>
          </li>
        @endif
      </ul>

    <div class="tab-content">

      <label>{{ trans('web::seat.standings') }}: </label>
      <label id="contact-standing" class="checkbox-inline">
        <input type="checkbox" value="-10" checked>-10
      </label>
      <label id="contact-standing" class="checkbox-inline">
        <input type="checkbox" value="-5" checked>-5
      </label>
      <label id="contact-standing" class="checkbox-inline">
        <input type="checkbox" value="0" checked>0
      </label>
      <label id="contact-standing"class="checkbox-inline">
        <input type="checkbox" value="5" checked>5
      </label>
      <label id="contact-standing" class="checkbox-inline">
        <input type="checkbox" value="10" checked>10
      </label>

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

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      var target = $(e.target).data("characters"); // activated tab
      contact_table.draw();
    });

    function allLinkedCharacters() {
      var character_ids = $("div.nav-tabs-custom > ul > li.active > a").data('characters');
      return character_ids !== 'single';
    }

    function standingCeckboxes() {
      return $('#contact-standing > input[type="checkbox"]').map(function(){
        if(this.checked)
          return $(this).val()
      }).get().join();
    }

    $( '#contact-standing > input[type="checkbox"]' ).click(function() {
      contact_table.draw();
    });

    var contact_table = $('#contact-table').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        url: "{{url()->current()}}",
        data: function ( d ) {
          d.all_linked_characters = allLinkedCharacters();
          d.selected_standings = standingCeckboxes();
        }
      },
      columns: [
        {data: 'name', name: 'name', sortable: false},
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

        if (data.is_in_group === "1"){
          $(row).addClass('info')
        }
      },
      drawCallback : function () {
        $("img").unveil(100);
        ids_to_names();
      },
    });

  </script>

@endpush
