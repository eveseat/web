@extends('web::corporation.layouts.view', ['viewname' => 'starbases', 'breadcrumb' => trans_choice('web::seat.starbase', 2)])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.starbase', 2))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="row">
    <div class="col-md-12">

      @include('web::corporation.starbase.summary')

    </div>
  </div> <!-- ./row -->

  @foreach($starbases as $starbase)

    <div class="row">
      <div class="col-md-12">

        <div class="card hidden" id="starbaseDetail{{ $starbase->starbase_id }}">
          <div class="card-header">
            <h3 class="card-title">
              {{ $starbase->type->name }} <i>({{ $starbase->moon->name }})</i>
            </h3>
          </div>
          <div class="card-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
              <li class="nav-item">
                <a href="#status{{ $starbase->starbase_id }}" aria-controls="status{{ $starbase->starbase_id }}"
                   role="tab" data-toggle="pill" class="nav-link active">Status</a>
              </li>
              <li class="nav-item">
                <a href="#modules{{ $starbase->starbase_id }}" aria-controls="modules{{ $starbase->starbase_id }}"
                   id="modules-tab" a-starbase-id="{{ $starbase->starbase_id }}"
                   role="tab" data-toggle="pill" class="nav-link">{{ trans_choice('web::seat.module', 2) }}</a>
              </li>
            </ul>

            <div class="tab-content p-3">

              @include('web::corporation.starbase.status-tab')

              <div role="tabpanel" class="tab-pane" id="modules{{ $starbase->starbase_id }}" a-ajax-loaded="false">
                <!-- ajax placeholder div -->
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>

  @endforeach

@stop

@push('javascript')

  <script>
    $("a#modules-tab").on('click', function () {

      // Grab the starbaseID
      var starbase_id = $(this).attr('a-starbase-id');

      // Prevent loading the request *again* if its already been
      // successfully loaded!
      if ($("div#modules" + starbase_id).attr('a-ajax-loaded') === "false") {

        // 'Loading' animation
        $("div#modules" + starbase_id)
            .html('<i class="fa fa-cog fa fa-spin"></i> {{ trans('web::seat.loading_modules') }}</p>');

        $.ajax({
          type   : 'POST',
          url    : "{{ route('seatcore::corporation.view.starbase.modules', ['corporation' => $corporation]) }}",
          data   : {
            'starbase_id': starbase_id
          },
          success: function (result) {
            $("div#modules" + starbase_id)
                .html(result)
                .attr('a-ajax-loaded', 'true');
          },
          error  : function (xhr, textStatus, errorThrown) {
            console.error(xhr);
            console.error(textStatus);
            console.error(errorThrown);
          }
        });
      }
    });
  </script>

  @include('web::includes.javascript.id-to-name')

@endpush
