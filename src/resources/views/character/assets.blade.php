@extends('web::character.layouts.view', ['viewname' => 'assets'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.assets'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.assets'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">
        {{ trans('web::seat.assets') }}

      </h3>

      @if(auth()->user()->has('character.jobs'))
        <div class="box-tools pull-right">
          <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.assets']) }}"
             style="color: #000000">
            <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_assets') }}"></i>
          </a>
        </div>
      @endif

    </div>
    <div class="box-body">
      <table class="table compact table-condensed table-hover table-responsive" id="assets">
        <thead>
        <tr>
          <th></th>
          <th>{{ trans('web::seat.quantity') }}</th>
          <th>{{ trans_choice('web::seat.type', 1) }}</th>
          <th>{{ trans('web::seat.volume') }}</th>
          <th>{{ trans_choice('web::seat.group',1) }}</th>
        </tr>
        </thead>
      </table>
    </div>
    <!-- /.box-body -->
    <!-- Loading (remove the following to stop the loading)-->
    <div class="overlay">
      <i class="fa fa-refresh fa-spin"></i>
    </div>
    <!-- end loading -->
  </div>


@stop

@push('javascript')

  <script type="text/javascript">
    $(document).ready(function () {
      $.ajax(
          {
            url       : "{{route('character.view.assets',['character_id' => $request->character_id])}}",
            dataType  : 'json',
            beforeSend: function () {
              $('.overlay').show();
            }
          })
          .done(function (data) {
            $('.overlay').hide();
            $("#assets").append(data.html);
            $(".viewcontent").on("click", function () {
              var attribute_box = $(this);
              var contents = $(this).closest('tr').next('tr');
              // Show or hide
              contents.toggle();
              // Styling
              if (contents.is(":visible")) {
                // Get the assets contents
                // shitty load
                // Apply some styling
                $(this).find('i').removeClass("fa-plus").addClass("fa-minus");
                $(this).closest("tr").css("background-color", "#D4D4D4"); // Heading Color
                contents.css("background-color", "#E5E5E5");              // Table Contents Color
              } else {
                $(this).find('i').removeClass("fa-minus").addClass("fa-plus");
                $(this).closest("tr").css("background-color", "");
              }
            });
          })
          .fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('server not responding...');
          });
    });
  </script>

@endpush