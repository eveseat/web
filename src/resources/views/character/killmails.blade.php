@extends('web::character.layouts.view', ['viewname' => 'killmails', 'breadcrumb' => trans('web::seat.killmails')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.killmails'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#" data-toggle="tab" data-characters="single">{{ trans('web::seat.killmails') }}</a></li>
      <li><a href="#" data-toggle="tab" data-characters="all">{{ trans('web::seat.linked_characters') }} {{ trans('web::seat.killmails') }}</a></li>
      @if (auth()->user()->has('character.jobs') )
        <li class="pull-right">
          <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.killmails']) }}"
             style="color: #000000">
            <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_killmails') }}"></i>
          </a>
        </li>
      @endif
    </ul>
    <div class="tab-content">

      <table class="table compact table-condensed table-hover table-responsive"
             id="character-killmails" data-page-length=100>
        <thead>
        <tr>
          <th>{{ trans('web::seat.date') }}</th>
          <th>{{ trans('web::seat.ship_type') }}</th>
          <th>{{ trans('web::seat.location') }}</th>
          <th>{{ trans('web::seat.victim') }}</th>
          <th data-orderable="false"></th>
        </tr>
        </thead>
      </table>

    </div>

  </div>

@stop

@push('javascript')

  <script>

    $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
      character_killmails.draw();
    });

    function allLinkedCharacters() {
      var character_ids = $("div.nav-tabs-custom > ul > li.active > a").data('characters');
      return character_ids !== 'single';
    }


    var character_killmails = $('table#character-killmails').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: '{{ route('character.view.killmails.data', ['character_id' => $request->character_id]) }}',
        data: function ( d ) {
          d.all_linked_characters = allLinkedCharacters();
        }
      },
      columns: [
        {data: 'killmail_detail.killmail_time', name: 'killmail_detail.killmail_time', render: human_readable},
        {data: 'ship', name: 'killmail_victim.ship_type.typeName'},
        {data: 'place', name: 'killmail_detail.solar_system.itemName'},
        {data: 'victim', name: 'killmail_victim.victim_character.name'},
        {data: 'zkb', name: 'zkb', searchable: false},
        {data: 'killmail_hash', name: 'killmail_victim.victim_corporation.name', visible: false},
        {data: 'killmail_id', name: 'killmail_victim.victim_alliance.name', visible: false},
      ],
      drawCallback: function () {
        $('img').unveil(100);
        ids_to_names();
      }
    });


  </script>

@endpush
