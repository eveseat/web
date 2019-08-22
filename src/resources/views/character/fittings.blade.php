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

      {{ $dataTable->table() }}

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

  @include('web::common.fittings.modals.insurances.insurances')

@stop

@push('javascript')

  {{ $dataTable->scripts() }}

  <script>
      $('#insurances-detail').on('show.bs.modal', function (e) {
          var body = $(e.target).find('.modal-body');
          body.html('Loading...');

          $.ajax($(e.relatedTarget).data('url'))
              .done(function (data) {
                  body.html(data);
              });
      });
  </script>

@endpush
