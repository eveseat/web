<div role="tabpanel" class="tab-pane active" id="status{{ $starbase->itemID }}">

  <div class="row">
    <div class="col-md-8">

      <dl class="dl-horizontal">

        <dt>{{ trans('web::seat.state') }}:</dt>
        <dd>
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
        </dd>

        <dt>{{ trans('web::seat.onlined_at') }}:</dt>
        <dd>
          {{ $starbase->onlineTimeStamp }}
          <i>({{ human_diff($starbase->onlineTimeStamp) }})</i>
        </dd>

        <dt>{{ trans('web::seat.last_update') }}:</dt>
        <dd>
          {{ $starbase->updated_at }}
          <i>({{ human_diff($starbase->updated_at) }})</i>
        </dd>

        <dt>{{ trans_choice('web::seat.type', 1) }}:</dt>
        <dd>
          {!! img('type', $starbase->starbaseTypeID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
          {{ $starbase->starbaseTypeName }}
        </dd>

        <dt>{{ trans_choice('web::seat.name', 1) }}:</dt>
        <dd>{{ $starbase->starbaseName }}</dd>

      </dl>

      <dl class="dl-horizontal">

        <dt>{{ trans('web::seat.system') }}</dt>
        <dd>{{ $starbase->mapName }}</dd>

        <dt>{{ trans('web::seat.moon') }}</dt>
        <dd>{{ $starbase->moonName }}</dd>

        <dt>{{ trans('web::seat.security') }}</dt>
        <dd>
          <span class="@if($starbase->mapSecurity >= 0.5)
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

        <dt>{{ trans('web::seat.use_standings_from') }}:</dt>
        <dd>
          {!! img('auto', $starbase->useStandingsFrom, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
          <span rel="id-to-name">{{ $starbase->useStandingsFrom }}</span>
        </dd>

        <dt>{{ trans('web::seat.attack_on_agression') }}:</dt>
        <dd>
          @if($starbase->onAggression)
            {{ trans('web::seat.yes') }}
          @else
            {{ trans('web::seat.no') }}
          @endif
        </dd>

        <dt>{{ trans('web::seat.attack_on_war') }}:</dt>
        <dd>
          @if($starbase->onCorporationWarn)
            {{ trans('web::seat.yes') }}
          @else
            {{ trans('web::seat.no') }}
          @endif
        </dd>

        <dt>{{ trans('web::seat.corp_member_access') }}:</dt>
        <dd>
          @if($starbase->allowCorporationMembers)
            {{ trans('web::seat.yes') }}
          @else
            {{ trans('web::seat.no') }}
          @endif
        </dd>

        <dt>{{ trans('web::seat.alliance_member_access') }}:</dt>
        <dd>
          @if($starbase->allowAllianceMembers)
            {{ trans('web::seat.yes') }}
          @else
            {{ trans('web::seat.no') }}
          @endif
        </dd>

      </dl>

    </div>
    <div class="col-md-4">

      <p class="text-center">
        <strong>{{ trans('web::seat.fuel_levels') }}</strong>
      </p>

      <div class="progress-group">
        <span class="progress-text">
          {!! img('type', 4051, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
          {{ trans('web::seat.fuel_blocks') }}
        </span>
        <span class="progress-number">
          <b>{{ number($starbase->fuelBlocks, 0) }}</b>/{{ number($starbase->fuelBaySize/5, 0) }}
          {{ trans_choice('web::seat.unit', 2) }}
        </span>

        <div class="progress sm">
          <div class="progress-bar"
               style="width: {{ 100 * (($starbase->fuelBlocks * 5)/$starbase->fuelBaySize) }}%">
          </div>
        </div>
      </div>
      <!-- /.progress-group -->
      <div class="progress-group">
        <span class="progress-text">
          {!! img('type', 16275, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
          Strontium
        </span>
        <span class="progress-number">
          <b>{{ number($starbase->strontium, 0) }}</b>/{{ number($starbase->strontBaySize/3, 0) }}
          {{ trans_choice('web::seat.unit', 2) }}
        </span>

        <div class="progress sm">
          <div class="progress-bar progress-bar-green"
               style="width: {{ 100 * (($starbase->strontium * 3)/$starbase->strontBaySize) }}%">
          </div>
        </div>
      </div>
      <!-- /.progress-group -->

      <hr>

      <dl>

        <dt>{{ trans('web::seat.sov_bonus') }}</dt>
        <dd>
          @if($starbase->inSovSystem)
            <span class="text-success">
              {{ trans('web::seat.yes') }}
            </span>
          @else
            {{ trans('web::seat.no') }}
          @endif
        </dd>

        <dt>{{ trans('web::seat.fuel_usage') }}</dt>
        <dd>
          @if($starbase->inSovSystem)
            {{ ceil($starbase->baseFuelUsage * 0.75) }}
          @else
            {{ $starbase->baseFuelUsage }}
          @endif
          {{ trans('web::seat.blocks_p_h') }}
        </dd>

        <dt>{{ trans('web::seat.stront_usage') }}</dt>
        <dd>
          @if($starbase->inSovSystem)
            {{ ceil($starbase->baseStrontUsage * 0.75) }}
          @else
            {{ $starbase->baseStrontUsage }}
          @endif

          {{ trans('web::seat.units_p_h') }}
        </dd>

        <dt>{{ trans('web::seat.offline') }}</dt>
        <dd>
          @if($starbase->inSovSystem)
            {{
              carbon('now')->addHours($starbase->fuelBlocks / ceil($starbase->baseFuelUsage * 0.75))
                ->diffForHumans()
            }} at
            {{
              carbon('now')->addHours($starbase->fuelBlocks / ceil($starbase->baseFuelUsage * 0.75))
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

        <dt>{{ trans('web::seat.reinforce_estimate') }}</dt>
        <dd>
          @if($starbase->state == 3)
            {{ $starbase->stateTimeStamp }}
            <i>({{ human_diff($starbase->stateTimeStamp) }})</i>
          @else
            @if($starbase->inSovSystem)
              {{
                round($starbase->strontium/ ceil($starbase->baseStrontUsage * 0.75))
              }} hours at
              {{
                carbon('now')->addHours($starbase->strontium / ceil($starbase->baseStrontUsage * 0.75))
              }}
            @else
              {{
                round(($starbase->strontium/$starbase->baseStrontUsage))
              }} hours at
              {{
                carbon('now')->addHours($starbase->strontium/$starbase->baseStrontUsage)
              }}
            @endif
          @endif
        </dd>

      </dl>

    </div>
  </div>

</div>
