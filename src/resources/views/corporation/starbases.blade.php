@extends('web::corporation.layouts.view', ['viewname' => 'starbases'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.starbase', 2))
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

        <div class="panel panel-default" id="starbaseDetail{{ $starbase->itemID }}">
          <div class="panel-heading">
            <h3 class="panel-title">
              {{ $starbase->starbaseName }} <i>({{ $starbase->moonName }})</i>
            </h3>
          </div>
          <div class="panel-body">
            <div>

              <!-- Nav tabs -->
              <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                  <a href="#status{{ $starbase->itemID }}" aria-controls="status{{ $starbase->itemID }}"
                     role="tab" data-toggle="tab">Status</a>
                </li>
                <li role="presentation">
                  <a href="#modules{{ $starbase->itemID }}" aria-controls="modules{{ $starbase->itemID }}"
                     id="modules-tab" a-starbase-id="{{ $starbase->itemID }}"
                     role="tab" data-toggle="tab">{{ trans_choice('web::seat.module', 2) }}</a>
                </li>
              </ul>

              <div class="tab-content">

                @include('web::corporation.starbase.status-tab')

                <div role="tabpanel" class="tab-pane" id="modules{{ $starbase->itemID }}"
                     a-ajax-loaded="false">
                  <!-- ajax placeholder div -->
                </div>

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
  $("a#modules-tab").click(function () {

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
        url    : "{{ route('corporation.view.starbase.modules',
          ['corporation_id' => $request->corporation_id]) }}",
        data   : {
          'starbase_id': starbase_id
        },
        success: function (result) {
          $("div#modules" + starbase_id)
              .html(result)
              .attr('a-ajax-loaded', 'true');
        },
        error  : function (xhr, textStatus, errorThrown) {
          console.log(xhr);
          console.log(textStatus);
          console.log(errorThrown);
        }
      });
    }
  });
</script>

@include('web::includes.javascript.id-to-name')

@endpush
