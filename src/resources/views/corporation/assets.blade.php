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
          <th>{{ trans('web::seat.quantity') }}</th>
          <th>{{ trans_choice('web::seat.type', 1) }}</th>
          <th>{{ trans('web::seat.volume') }}</th>
          <th>{{ trans('web::seat.group') }}</th>
        </tr>
        </thead>

        @foreach($assets->unique('location')->groupBy('location') as $location => $data)

          <tbody style="border-top: 0px;">

          <tr class="active">
            <td colspan="4">
              <b>{{ $location }}</b>
              <span class="pull-right">
                <i>
                  {{ count($assets->where('locationID', $data[0]->locationID)) }}
                  {{ trans('web::seat.items_taking') }}
                  {{ number_metric($assets
                      ->where('locationID', $data[0]->locationID)->map(function($item) {
                            return $item->quantity * $item->volume;
                  })->sum()) }} m&sup3;
                </i>
              </span>
            </td>
          </tr>

          </tbody>

          @foreach($assets->where('locationID', $data[0]->locationID) as $asset)

            <tbody style="border-top: 0px;">

            <tr>
              @if($asset->childContentCount > 0)

                <td>
                  <i class="fa fa-plus viewcontent" style="cursor: pointer;"
                     a-item-id="{{ $asset->itemID }}" a-loaded="false">
                  </i>
                </td>

              @else

                <td>{{ $asset->quantity }}</td>

              @endif

              <td>
                {!! img('type', $asset->typeID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
                {{ $asset->typeName }}
              </td>
              <td>{{ number_metric($asset->quantity * $asset->volume) }} m&sup3;</td>
              <td>{{ $asset->groupName }}</td>
            </tr>

            </tbody>

            @if($asset->childContentCount > 0)

              <tbody style="display: none;" class="tbodycontent">
              <!-- assets contents populated via ajax call -->
              </tbody>

            @endif

          @endforeach

        @endforeach

      </table>

    </div><!-- /.box-body -->
  </div>

@stop

@push('javascript')

<script type="text/javascript">

  $(".viewcontent").on("click", function () {

    var attribute_box = $(this);

    var contents = $(this).closest("tbody").next("tbody");

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
      $(this).removeClass("fa-plus").addClass("fa-minus");
      $(this).closest("tr").css("background-color", "#D4D4D4"); // Heading Color
      contents.css("background-color", "#E5E5E5");              // Table Contents Color

    } else {

      $(this).removeClass("fa-minus").addClass("fa-plus");
      $(this).closest("tr").css("background-color", "");

    }
  });

</script>

@endpush
