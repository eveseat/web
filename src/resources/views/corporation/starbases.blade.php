@extends('web::corporation.layouts.view', ['viewname' => 'starbases'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.starbase', 2))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.starbase', 2))

@section('corporation_content')

  <div class="row">

    <div class="col-md-12">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans_choice('web::seat.starbase', 2) }}</h3>
        </div>
        <div class="panel-body">

          <table class="table table-condensed table-hover table-responsive">
            <tbody>
            <tr>
              <th>{{ trans_choice('web::seat.state', 1) }}</th>
              <th>{{ trans_choice('web::seat.type', 1) }}</th>
              <th>{{ trans_choice('web::seat.location', 1) }}</th>
              <th>
                {!! img('type', 4051, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                {{ trans('web::seat.fuel_level') }}
              </th>
              <th>{{ trans_choice('web::seat.offline', 1) }}</th>
            </tr>

            @foreach($starbases as $starbase)

              <tr>
                <td>
              <span data-toggle="tooltip"
                    title="" data-original-title="Last Update: {{ $starbase->updated_at }}">
                <span class="label
                      @if($starbase->state == 4)
                        label-success
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
                <td>
                  <div class="progress">
                    <div class="progress-bar" role="progressbar"
                         aria-valuenow="60" aria-valuemin="0"
                         aria-valuemax="100"
                         style="width: {{ 100 * (($starbase->fuelBlocks * 5)/$starbase->fuelBaySize) }}%">
                    </div>
                  </div>
                </td>
                <td>
                  @if($starbase->inSovSystem)
                    {{
                      carbon('now')->addHours(($starbase->fuelBlocks/$starbase->baseFuelUsage) * 0.75)
                        ->diffForHumans()
                    }}
                  @else
                    {{
                      carbon('now')->addHours($starbase->fuelBlocks/$starbase->baseFuelUsage)
                        ->diffForHumans()
                    }}
                  @endif
                </td>
              </tr>

            @endforeach

            </tbody>
          </table>

        </div>
        <div class="panel-footer">
          {{ count($starbases) }} {{ trans_choice('web::seat.starbase', count($starbases)) }}
        </div>
      </div>

    </div>

  </div> <!-- ./row -->

  @foreach($starbases as $starbase)

    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ $starbase->starbaseName }}</h3>
          </div>
          <div class="panel-body">

            <div class="row">
              <div class="col-md-8">

                <dl class="dl-horizontal">

                  <dt>State:</dt>
                  <dd>
                    <span class="label
                      @if($starbase->state == 4)
                            label-success
                          @else
                            label-warning
                          @endif">
                      {{ $starbase_states[$starbase->state] }}
                    </span>
                  </dd>

                  <dt>Onlined At:</dt>
                  <dd>
                    {{ $starbase->onlineTimeStamp }}
                    <i>({{ human_diff($starbase->onlineTimeStamp) }})</i>
                  </dd>

                  <dt>API Updated At:</dt>
                  <dd>
                    {{ $starbase->updated_at }}
                    <i>({{ human_diff($starbase->updated_at) }})</i>
                  </dd>

                  <dt>Type:</dt>
                  <dd>
                    {!! img('type', $starbase->starbaseTypeID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                    {{ $starbase->starbaseTypeName }}
                  </dd>

                  <dt>Name:</dt>
                  <dd>{{ $starbase->starbaseName }}</dd>

                </dl>

                <dl class="dl-horizontal">

                  <dt>System</dt>
                  <dd>{{ $starbase->mapName }}</dd>

                  <dt>Moon</dt>
                  <dd>{{ $starbase->moonName }}</dd>

                  <dt>Security</dt>
                  <dd>
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
                  </dd>
                </dl>

                <dl class="dl-horizontal">

                  <dt>Use Standings From:</dt>
                  <dd>
                    {!! img('auto', $starbase->useStandingsFrom, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                    <span rel="id-to-name">{{ $starbase->useStandingsFrom }}</span>
                  </dd>

                  <dt>Attack on Agression:</dt>
                  <dd>
                    @if($starbase->onAggression)
                      Yes
                    @else
                      No
                    @endif
                  </dd>

                  <dt>Attack on Corp War:</dt>
                  <dd>
                    @if($starbase->onCorporationWarn)
                      Yes
                    @else
                      No
                    @endif
                  </dd>

                  <dt>Corp Member Access:</dt>
                  <dd>
                    @if($starbase->allowCorporationMembersn)
                      Yes
                    @else
                      No
                    @endif
                  </dd>

                  <dt>Alliance Member Access:</dt>
                  <dd>
                    @if($starbase->allowAllianceMembers)
                      Yes
                    @else
                      No
                    @endif
                  </dd>

                </dl>

              </div>
              <div class="col-md-4">

                <p class="text-center">
                  <strong>Fuel Levels</strong>
                </p>
                <div class="progress-group">
                  <span class="progress-text">
                    {!! img('type', 4051, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                    Fuel Blocks
                  </span>
                  <span class="progress-number">
                    <b>{{ number($starbase->fuelBlocks, 0) }}</b>/{{ number($starbase->fuelBaySize/5, 0) }}
                    units
                  </span>
                  <div class="progress sm">
                    <div class="progress-bar"
                         style="width: {{ 100 * (($starbase->fuelBlocks * 5)/$starbase->fuelBaySize) }}%"></div>
                  </div>
                </div><!-- /.progress-group -->
                <div class="progress-group">
                  <span class="progress-text">
                    {!! img('type', 16275, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                    Strontium
                  </span>
                  <span class="progress-number">
                    <b>{{ number($starbase->strontium, 0) }}</b>/{{ number($starbase->strontBaySize/3, 0) }}
                    units
                  </span>
                  <div class="progress sm">
                    <div class="progress-bar progress-bar-green"
                         style="width: {{ 100 * (($starbase->strontium * 3)/$starbase->strontBaySize) }}%"></div>
                  </div>
                </div><!-- /.progress-group -->

                <hr>

                <dl class="">

                  <dt>Sov Bonus</dt>
                  <dd>
                    @if($starbase->inSovSystem)
                      <span class="text-success">
                        Yes
                      </span>
                    @else
                      No
                    @endif
                  </dd>

                  <dt>Fuel Block Usage</dt>
                  <dd>
                    @if($starbase->inSovSystem)
                      {{ $starbase->baseFuelUsage * 0.75 }}
                    @else
                      {{ $starbase->baseFuelUsage }}
                    @endif
                    blocks p/h
                  </dd>

                  <dt>Strontium Usage</dt>
                  <dd>
                    @if($starbase->inSovSystem)
                      {{ $starbase->baseStrontUsage * 0.75}}
                    @else
                      {{ $starbase->baseStrontUsage }}
                    @endif

                    units p/h
                  </dd>

                  <dt>Offline Estimate</dt>
                  <dd>
                    @if($starbase->inSovSystem)
                      {{
                        carbon('now')->addHours(($starbase->fuelBlocks/$starbase->baseFuelUsage) * 0.75)
                          ->diffForHumans()
                      }} at
                      {{
                        carbon('now')->addHours(($starbase->fuelBlocks/$starbase->baseFuelUsage) * 0.75)
                      }}
                    @else
                      {{
                        carbon('now')->addHours($starbase->fuelBlocks/$starbase->baseFuelUsage)
                          ->diffForHumans()
                      }} at
                      {{
                        carbon('now')->addHours($starbase->fuelBlocks/$starbase->baseFuelUsage)
                      }}
                    @endif
                  </dd>

                  <dt>Reinforce Estimate</dt>
                  <dd>
                    @if($starbase->inSovSystem)
                      {{
                        round(($starbase->strontium/$starbase->baseStrontUsage) * 0.75)
                      }} hours at
                      {{
                        carbon('now')->addHours(($starbase->strontium/$starbase->baseStrontUsage) * 0.75)
                      }}
                    @else
                      {{
                        round(($starbase->strontium/$starbase->baseStrontUsage))
                      }} hours at
                      {{
                        carbon('now')->addHours($starbase->strontium/$starbase->baseStrontUsage)
                      }}
                    @endif
                  </dd>

                </dl>

              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

  @endforeach

@stop

@section('javascript')

  @include('web::includes.javascript.id-to-name')

@stop
