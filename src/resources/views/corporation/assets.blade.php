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
          <th>{{ trans_choice('web::seat.group',1) }}</th>
          <th>{{ trans('web::seat.location_flag') }}</th>
        </tr>
        </thead>

        <tbody>

        @foreach($assets->unique('location_id')->groupBy('location_id') as $location)

          <tbody style="border-top: 0px;">

          <tr class="active">
            <td colspan="5">
              <b>
                @if($location->first()->location_name == '')
                  Unknown Structure ({{ $location->first()->location_id }})
                @else
                  {{ $location->first()->location_name }}
                @endif
              </b>
              <span class="pull-right">
                    <i>
                      {{ count($assets->where('location_id', $location->first()->location_id)) }}
                      {{ trans('web::seat.items_taking') }}
                      {{ number_metric($assets
                          ->where('location_id', $location->first()->location_id)->map(function($item) {
                            return $item->quantity * $item->type->volume;
                      })->sum()) }} m&sup3;
                    </i>
                  </span>
            </td>
          </tr>

          </tbody>

          @foreach($assets->where('location_id', $location->first()->location_id) as $asset)

            <tbody style="border-top: 0px;">

            <tr>
              @if($asset->content->count() > 0)

                <td>
                  <i class="fa fa-plus viewcontent" style="cursor: pointer;"
                     a-item-id="{{ $asset->item_id }}" a-loaded="false">
                  </i>
                </td>

              @else

                <td>{{ $asset->quantity }}</td>

              @endif

              <td>
                {!! img('type', $asset->type_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
                @if($asset->name != $asset->type->typeName)
                  {{ $asset->name }} ({{ $asset->type->typeName }})
                @else
                  {{ $asset->type->typeName }}
                @endif
                @if(! $asset->is_singleton)
                  <span class="text-red">(packaged)</span>
                @endif
              </td>
              <td>{{ number_metric($asset->quantity * $asset->type->volume) }} m&sup3;</td>
              <td>{{ $asset->type->group->groupName }}</td>
              <td>
                @if(str_contains($asset->location_flag, 'CorpSAG'))
                  {{
                    $divisions->where('division', str_after($asset->location_flag, 'CorpSAG'))->pluck('name')->first()
                  }}
                @else
                  {{ $asset->location_flag }}
                @endif
              </td>
            </tr>

            </tbody>

            @if($asset->content->count() > 0)

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

      if (contents.is(":visible")) {

        // Get the assets contents
        if (attribute_box.attr('a-loaded') == 'false') {

          // Small hack to get an ajaxable url from Laravel
          var url = "{{ route('corporation.view.assets.contents', ['corporation_id' => $request -> corporation_id, 'item_id' => ':item_id']) }}";
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
