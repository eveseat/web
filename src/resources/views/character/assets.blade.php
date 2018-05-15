@extends('web::character.layouts.view', ['viewname' => 'assets'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.assets'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.assets'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

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

      <table class="table compact table-condensed table-hover table-responsive">
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
        @foreach($assets->whereIn('location_flag', ['Hangar', 'AssetSafety', 'Deliveries'])->sortBy('locationName')->groupBy('location_id') as $location)
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
                <i>{{ $assets->where('location_id', $location->first()->location_id)->count() }}
                  {{ trans('web::seat.items_taking') }}
                  {{
                    number_metric($assets->where('location_id', $location->first()->location_id)->map(
                      function($value){
                        return $value->quantity * optional($value->type)->volume ?? 0;
                      })->sum()
                    )
                  }}
                  m&sup3;</i>
              </span>
            </td>
          </tr>

          @foreach($assets->where('location_id', $location->first()->location_id) as $container)

            <tr>
              <td>
                @if($container->content->count() > 0)
                  <button class="btn btn-xs btn-link viewcontent">
                    <i class="fa fa-plus"></i>
                  </button>
                @endif
              </td>
              <td>
                @if($container->content->count() < 1)
                  {{ number($container->quantity, 0) }}
                @endif
              </td>
              <td>
                {!! img('type', $container->type_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
                @if($container->type)
                  @if($container->name != $container->type->typeName)
                    {{ $container->name }} ({{ $container->type->typeName }})
                  @else
                    {{ $container->type->typeName }}
                  @endif
                @else
                  Unknown
                @endif
                @if(! $container->is_singleton)
                  <span class="text-red">(packaged)</span>
                @endif
              </td>
              <td>{{ number_metric($container->quantity * optional($container->type)->volume ?? 0) }}m&sup3;</td>
              <td>
                @if($container->type)
                  {{ $container->type->group->groupName }}
                @else
                  Unknown
                @endif
              </td>
            </tr>

            @if($container->content->count() > 0)
              <tr style="display: none;">
                <td colspan="5">
                  <table class="table compact table-condensed table-hover table-responsive">
                    <tbody>
                    @foreach($container->content as $content)
                      <tr>
                        <td>
                          @if($content->content->count() > 0)
                            <button class="btn btn-xs btn-link viewcontent">
                              <i class="fa fa-plus"></i>
                            </button>
                          @endif
                        </td>
                        <td>
                          @if($content->content->count() < 1)
                            {{ number($content->quantity, 0) }}
                          @endif
                        </td>
                        <td>{!! img('type', $content->type_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!} {{ $content->type->typeName }}</td>
                        <td>{{ number_metric($content->quantity * $content->type->volume) }}m&sup3;</td>
                        <td>{{ $content->type->group->groupName }}</td>
                      </tr>
                      @if($content->content->count() > 0)
                        <tr style="display: none;">
                          <td colspan="5">
                            <table class="table compact table-condensed table-hover table-responsive">
                              <tbody>
                              @foreach($content->content as $cargo)
                                <tr>
                                  <td></td>
                                  <td>{{ number($cargo->quantity, 0) }}</td>
                                  <td>{!! img('type', $cargo->type_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!} {{ $cargo->type->typeName }}</td>
                                  <td>{{ number_metric($cargo->quantity * $cargo->type->volume) }}m&sup3;</td>
                                  <td>{{ $cargo->type->group->groupName }}</td>
                                </tr>
                              @endforeach
                              </tbody>
                            </table>
                          </td>
                        </tr>
                      @endif
                    @endforeach
                    </tbody>
                  </table>
                </td>
              </tr>
            @endif

          @endforeach

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

  </script>

@endpush
