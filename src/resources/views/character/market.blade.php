@extends('web::character.layouts.view', ['viewname' => 'market', 'breadcrumb' => trans('web::seat.market')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.market'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#" data-toggle="tab" data-characters="single">{{ trans('web::seat.market') }}</a></li>
      <li><a href="#" data-toggle="tab" data-characters="all">{{ trans('web::seat.linked_characters') }} {{ trans('web::seat.market') }}</a></li>
      @if(auth()->user()->has('character.jobs'))
        <li class="pull-right">
          <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.market']) }}"
             style="color: #000000">
            <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_market') }}"></i>
          </a>
        </li>
      @endif
    </ul>

    <div class="tab-content">

      <table class="table compact table-condensed table-hover table-responsive"
             id="character-market">
        <thead>
        <tr>
          <th>{{ trans('web::seat.date') }}</th>
          <th></th>
          <th>{{ trans('web::seat.volume') }}</th>
          <th>{{ trans('web::seat.price') }}</th>
          <th>{{ trans('web::seat.total') }}</th>
          <th>{{ trans_choice('web::seat.type', 1) }}</th>
        </tr>
        </thead>
      </table>

    </div>
  </div>

@stop

@push('javascript')

  <script>
    $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
      character_market.draw();
    });

    function allLinkedCharacters() {

      var character_ids = $("div.nav-tabs-custom > ul > li.active > a").data('characters');
      return character_ids !== 'single';
    }

    var character_market = $('table#character-market').DataTable({
      processing  : true,
      serverSide  : true,
      ajax        : {
        url: '{{ route('character.view.market.data', ['character_id' => $request->character_id]) }}',
        data: function ( d ) {
          d.all_linked_characters = allLinkedCharacters();
        }
      },
      columns     : [
        {data: 'issued', name: 'issued', render: human_readable},
        {data: 'bs', name: 'is_buy_order'},
        {data: 'vol', name: 'volume_total'},
        {data: 'price', name: 'price'},
        {data: 'total', name: 'total'},
        {data: 'typeName', name: 'typeName'}
      ],
      drawCallback: function () {

        $('img').unveil(100);
        $('[data-toggle="tooltip"]').tooltip();
      }
    });


  </script>

@endpush
