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
        {!! img('type', $container->type_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
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
