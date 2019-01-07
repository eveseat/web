@extends('web::character.layouts.view', ['viewname' => 'fittings', 'breadcrumb' => trans('web::seat.fittings')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.fittings'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans('web::seat.fittings') }}
        @if(auth()->user()->has('character.jobs'))
          <span class="pull-right">
            <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.fittings']) }}"
               style="color: #000000">
              <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_fittings') }}"></i>
            </a>
          </span>
        @endif
      </h3>
    </div>
    <div class="panel-body">

      <table class="table datatable compact table-condensed table-hover table-responsive">
        <thead>
        <th>{{ trans_choice('web::seat.type', 1) }}</th>
        <th>{{ trans_choice('web::seat.name', 2) }}</th>
        <th>{{ trans_choice('web::seat.item', 2) }}</th>
        <th data-orderable="false"></th>
        </thead>
        <tbody>

        @foreach($fittings as $fitting)

          <tr>
            <td>{{ $fitting->name }}</td>
            <td>
              {!! img('type', $fitting->ship_type_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $fitting->shiptype->typeName }}
            </td>
            <td>{{ count($fitting->items) }}</td>
            <td>
              <a href="#" class="fitting-item" data-toggle="modal" data-target="#fittingItemsModal"
                 a-fitting-id="{{ $fitting->fitting_id }}">
                <i class="fa fa-expand"></i>
              </a>
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

  <!-- Fitting Items Modal -->
  <div class="modal fade" id="fittingItemsModal" tabindex="-1" role="dialog"
       aria-labelledby="fittingItemsModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">{{ trans('web::seat.fitting_items') }}</h4>
        </div>
        <div class="modal-body">

          <span id="fittings-items-result"></span>

        </div>
      </div>
    </div>
  </div>

@stop

@push('javascript')

  <script>

    $(document).ready(function () {

      // Load images when they are in the viewport
      $("img").unveil(100);

      // After loading the contracts data, bind a click event
      // on items with the contract-item class.
      $('a.fitting-item').on('click', function () {

        // Small hack to get an ajaxable url from Laravel
        var url = "{{ route('character.view.fittings.items', ['character_id' => $request->character_id, 'fitting_id' => ':fittingid']) }}";
        var fitting_id = $(this).attr('a-fitting-id');
        url = url.replace(':fittingid', fitting_id);

        // Perform an ajax request for the contract items
        $.get(url, function (data) {
          $('span#fittings-items-result').html(data);
        });

      });

    });

  </script>

@endpush
