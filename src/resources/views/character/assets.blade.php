@extends('web::character.layouts.view', ['viewname' => 'assets'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.assets'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.assets'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  {{--{{dd($assets)}}--}}

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans('web::seat.assets') }}
        @if(auth()->user()->has('character.jobs'))
          <span class="pull-right">
            <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.assets']) }}"
               style="color: #000000">
              <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_assets') }}"></i>
            </a>
          </span>
        @endif
      </h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive" id="post-data">
        <thead>
        <tr>
          <th></th>
          <th>{{ trans('web::seat.quantity') }}</th>
          <th>{{ trans_choice('web::seat.type', 1) }}</th>
          <th>{{ trans('web::seat.volume') }}</th>
          <th>{{ trans_choice('web::seat.group',1) }}</th>
        </tr>
        </thead>

        <tbody>
        <tr>
          <td>
            <button type="button" class="btn btn-xs btn-link" data-toggle="modal" data-target="#modal-default">
              <i class="fa fa-plus"></i>
            </button>
            <div class="modal fade" id="modal-default">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Default Modal</h4>
                  </div>
                  <div class="modal-body">
                    <p>One fine body…</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                  </div>
                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>
          </td>
          <td>2</td>
          <td>3</td>
          <td>4</td>
          <td>5</td>
        </tr>
        @include('web::character.partials.assets')
        </tbody>
      </table>

      <div class="ajax-load text-center" style="display:true">
        <i class="fa fa-spin fa-refresh"></i> Loading assets
      </div>

    </div><!-- /.box-body -->
  </div>

@stop

@push('javascript')

  <script type="text/javascript">

    $(".viewcontent").on("click", function () {

      var attribute_box = $(this);

      var contents = $(this).closest('tr').next('tr');

      // Show or hide
      contents.toggle();

      // Styling
      if (contents.is(":visible")) {

        // Get the assets contents

        if (attribute_box.attr('a-loaded') == 'false') {

          // Small hack to get an ajaxable url from Laravel
          var url = "{{ route('character.view.assets.contents', ['character_id' => $request->character_id, 'item_id' => ':item_id']) }}";
          var item_id = attribute_box.attr('a-item-id');
          url = url.replace(':item_id', item_id);

          // Perform an ajax request for the asset items
          $.get(url, function (data) {

            // Populate the tbody
            contents.html(data);

            // Mark the contents as loaded
            attribute_box.attr('a-loaded', 'true');

            // Re-init the lazy image loader
            $("img").unveil(100);
          });

        }

        // Apply some styling
        $(this).find('i').removeClass("fa-plus").addClass("fa-minus");
        $(this).closest("tr").css("background-color", "#D4D4D4"); // Heading Color
        contents.css("background-color", "#E5E5E5");              // Table Contents Color

      } else {

        $(this).find('i').removeClass("fa-minus").addClass("fa-plus");
        $(this).closest("tr").css("background-color", "");

      }
    });

    $(document).ready(function () {
      loadMoreData();
    });

    function loadMoreData() {
      $.ajax(
          {
            url       : "{{route('character.view.assets',['character_id' => $request->character_id])}}",
            dataType  : 'json',
            beforeSend: function () {
              $('.ajax-load').show();
            }
          })
          .done(function (data) {
            $('.ajax-load').hide();
            $("#post-data").append(data.html);

          })
          .fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('server not responding...');
          });
    }

  </script>

@endpush
