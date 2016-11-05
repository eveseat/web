<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{ trans_choice('web::seat.starbase', 2) }}</h3>
  </div>
  <div class="panel-body">

    <table class="table datatable compact table-condensed table-hover table-responsive">
      <thead>
      <tr>
        <th>{{ trans_choice('web::seat.state', 1) }}</th>
        <th>{{ trans_choice('web::seat.type', 1) }}</th>
        <th>{{ trans_choice('web::seat.location', 1) }}</th>
        <th>
          {!! img('type', 4051, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
          {{ trans('web::seat.fuel_level') }}
        </th>
        <th>{{ trans_choice('web::seat.offline', 1) }}</th>
        <th data-orderable="false"></th>
      </tr>
      </thead>
      <tbody>

      @foreach($starbases as $starbase)

        <tr>
          <td data-order="{{ $starbase->updated_at }}">
              <span data-toggle="tooltip"
                    title="" data-original-title="Last Update: {{ $starbase->updated_at }}">
                <span class="label
                      @if($starbase->state == 4)
                        label-success
                      @elseif($starbase->state == 0 || $starbase->state == 1)
                        label-danger
                      @else
                        label-warning
                      @endif">
                  {{ $starbase_states[$starbase->state] }}
                </span>
              </span>
          </td>
          <td>
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $starbase->starbaseTypeName }}">
                {!! img('type', $starbase->starbaseTypeID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                {{ $starbase->starbaseName }}
              </span>
          </td>
          <td>
            <b>{{ $starbase->moonName }}</b>
            <span class="
                @if($starbase->mapSecurity >= 0.5)
                    text-green
                  @elseif($starbase->mapSecurity < 0.5 && $starbase->mapSecurity > 0.0)
                    text-warning
                  @else
                    text-red
                  @endif">
                <i>({{ round($starbase->mapSecurity,  2) }})</i>
              </span>
          </td>
          <td data-order="{{ 100 * (($starbase->fuelBlocks * 5)/$starbase->fuelBaySize) }}">
            <div class="progress">
              <div class="progress-bar" role="progressbar"
                   aria-valuenow="60" aria-valuemin="0"
                   aria-valuemax="100"
                   style="width: {{ 100 * (($starbase->fuelBlocks * 5)/$starbase->fuelBaySize) }}%">
              </div>
            </div>
          </td>
          <td data-order="
            @if($starbase->inSovSystem)
          {{ carbon('now')->addHours($starbase->fuelBlocks / ceil($starbase->baseFuelUsage * 0.75)) }}
          @else
          {{ carbon('now')->addHours($starbase->fuelBlocks/$starbase->baseFuelUsage)  }}
          @endif
                  ">
            @if($starbase->inSovSystem)
              {{
                carbon('now')->addHours($starbase->fuelBlocks/ ceil($starbase->baseFuelUsage * 0.75))
                  ->diffForHumans()
              }}
            @else
              {{
                carbon('now')->addHours($starbase->fuelBlocks/$starbase->baseFuelUsage)
                  ->diffForHumans()
              }}
            @endif
          </td>
          <td>
            <a href="#starbaseDetail{{ $starbase->itemID }}">Detail</a>
          </td>
        </tr>

      @endforeach

      </tbody>
    </table>

  </div>
</div>
