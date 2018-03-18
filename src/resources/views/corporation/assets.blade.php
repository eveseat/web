@extends('web::corporation.layouts.view', ['viewname' => 'assets'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.assets'))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.assets'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.assets') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <thead>
          <tr>
            <th></th>
            <th>{{ trans('web::seat.quantity') }}</th>
            <th>{{ trans_choice('web::seat.type', 1) }}</th>
            <th>{{ trans('web::seat.volume') }}</th>
            <th>{{ trans('web::seat.group') }}</th>
          </tr>
        </thead>

        <tbody>

          @foreach($assets->sortBy('locationName')->groupBy('location_id') as $location)
          <tr class="active">
            <td colspan="5">
              <b>
                @if($location->first()->locationName == '')
                  Unknown Structure ({{ $location->first()->location_id }})
                @else
                  {{ $location->first()->locationName }}
                @endif
              </b>
              <span class="pull-right">
                <i>ITEM_NUMBER items taking ITEMS_VOLUME m&sup3;</i>
              </span>
            </td>
          </tr>

          {{-- asset office node --}}
          @if($assets->where('location_id', $location->first()->location_id)->where('location_flag', 'OfficeFolder')->count() > 0)
          <tr>
            <td colspan="5">Office
              <span class="pull-right">
                <i>ITEM_NUMBER items taking ITEMS_VOLUME m&sup3;</i>
              </span>
            </td>
          </tr>
          @foreach($divisions as $division)
          @if($assets->where('location_id', $location->first()->location_id)->where('location_flag', 'OfficeFolder')
              ->first()->content->where('location_flag', 'CorpSAG' . $division->division)->count() > 0)
          <tr>
            <td colspan="5">
                <button type="button" class="btn btn-xs btn-link">
                  <i class="fa fa-cubes"></i>
                </button>
                {{ $division->name }}
            </td>
          </tr>
          @endif
          @endforeach
          @endif

          {{-- asset safety node --}}
          @if($assets->where('location_id', $location->first()->location_id)->where('location_flag', 'AssetSafety')->count() > 0)
          <tr class="bg-yellow">
            <td colspan="5">Assets Safety
              <span class="pull-right">
                <i>ITEM_NUMBER items taking ITEMS_VOLUME m&sup3;</i>
              </span>
            </td>
          </tr>
          @endif

          {{-- asset delivery node --}}
          @if($assets->where('location_id', $location->first()->location_id)->where('location_flag', 'Deliveries')->count() > 0)
          <tr>
            <td colspan="5">Assets Deliveries
              <span class="pull-right">
                <i>ITEM_NUMBER items taking ITEMS_VOLUME m&sup3;</i>
              </span>
            </td>
          </tr>
          @endif
          @endforeach

        </tbody>

      </table>

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

    // Sstyling
    if (contents.is(":visible")) {

      // Get the assets contents

      if (attribute_box.attr('a-loaded') == 'false') {

        // Small hack to get an ajaxable url from Laravel
        var url = "{{ route('corporation.view.assets.contents', ['corporation_id' => $request->corporation_id, 'item_id' => ':item_id']) }}";
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

      // Apply some styleing
      $(this).find('i').removeClass("fa-plus").addClass("fa-minus");
      $(this).closest("tr").css("background-color", "#D4D4D4"); // Heading Color
      contents.css("background-color", "#E5E5E5");              // Table Contents Color

    } else {

      $(this).find('i').removeClass("fa-minus").addClass("fa-plus");
      $(this).closest("tr").css("background-color", "");

    }
  });

</script>

@endpush
