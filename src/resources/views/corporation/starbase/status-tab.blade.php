<div role="tabpanel" class="tab-pane active" id="status{{ $starbase->starbase_id }}">

  <div class="row">
    <div class="col-md-8">

      <dl class="dl-horizontal">

        <dt>{{ trans('web::seat.state') }}:</dt>
        <dd>
          <span class="badge
            @if($starbase->state == 'online')
            badge-success
            @elseif($starbase->state == 'unanchored' || $starbase->state == 'offline')
            badge-danger
            @else
            badge-warning
            @endif">
            {{ ucfirst($starbase->state) }}
          </span>
        </dd>

        <dt>{{ trans('web::seat.onlined_at') }}:</dt>
        <dd>
          {{ $starbase->onlined_since }}
          <i>({{ human_diff($starbase->onlined_since) }})</i>
        </dd>

        <dt>{{ trans('web::seat.last_update') }}:</dt>
        <dd>
          {{ $starbase->updated_at }}
          <i>({{ human_diff($starbase->updated_at) }})</i>
        </dd>

        <dt>{{ trans_choice('web::seat.type', 1) }}:</dt>
        <dd>
          @include('web::partials.type', ['type_id' => $starbase->type_id, 'type_name' => $starbase->type->typeName])
        </dd>

        <dt>{{ trans_choice('web::seat.name', 1) }}:</dt>
        <dd>{{ $starbase->item->name }}</dd>

      </dl>

      <dl class="dl-horizontal">

        <dt>{{ trans('web::seat.system') }}</dt>
        <dd>{{ $starbase->system->itemName }}</dd>

        <dt>{{ trans('web::seat.moon') }}</dt>
        <dd>{{ $starbase->moon->itemName }}</dd>

        <dt>{{ trans('web::seat.security') }}</dt>
        <dd>
          <span class="@if($starbase->moon->security >= 0.5)
                  text-green
                 @elseif($starbase->moon->security < 0.5 && $starbase->moon->security > 0.0)
                  text-warning
                 @else
                  text-red
                 @endif">
            <i>({{ round($starbase->moon->security,  2) }})</i>
          </span>
        </dd>
      </dl>

      <dl class="dl-horizontal">

        <dt>{{ trans('web::seat.use_standings_from') }}:</dt>
        <dd>
          @if($starbase->detail->use_alliance_standings)
          {!! img('alliances', 'logo', $sheet->alliance_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
            <span class="id-to-name" data-id="{{ $sheet->alliance_id }}">{{ trans('web::seat.unknown') }}</span>
          @else
          {!! img('corporations', 'logo', $starbase->corporation_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
            <span class="id-to-name" data-id="{{ $starbase->corporation_id }}">{{ trans('web::seat.unknown') }}</span>
          @endif
        </dd>

        <dt>{{ trans('web::seat.attack_on_agression') }}:</dt>
        <dd>
          @if($starbase->detail->attack_if_other_security_status_dropping)
            {{ trans('web::seat.yes') }}
          @else
            {{ trans('web::seat.no') }}
          @endif
        </dd>

        <dt>{{ trans('web::seat.attack_on_war') }}:</dt>
        <dd>
          @if($starbase->detail->attack_if_at_war)
            {{ trans('web::seat.yes') }}
          @else
            {{ trans('web::seat.no') }}
          @endif
        </dd>

        <dt>{{ trans('web::seat.corp_member_access') }}:</dt>
        <dd>
          @if($starbase->detail->allow_corporation_members)
            {{ trans('web::seat.yes') }}
          @else
            {{ trans('web::seat.no') }}
          @endif
        </dd>

        <dt>{{ trans('web::seat.alliance_member_access') }}:</dt>
        <dd>
          @if($starbase->detail->allow_alliance_members)
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
          @include('web::partials.type', ['type_id' => 4051, 'type_name' => trans('web::seat.fuel_blocks')])
        </span>
        <span class="progress-number">
          <b>{{ number_format(optional($starbase->fuelBays->whereIn('type_id', [4051, 4246, 4247, 4312, 36945]))->first()->quantity ?? 0, 0) }}</b>/{{ number_format($starbase->type->capacity / 5, 0) }}
          {{ trans_choice('web::seat.unit', 2) }}
        </span>

        <div class="progress sm">
          <div class="progress-bar"
               style="width: {{ 100 * ((optional($starbase->fuelBays->whereIn('type_id', [4051, 4246, 4247, 4312, 36945]))->first()->quantity ?? 0) * 5 / $starbase->type->capacity ) }}%">
          </div>
        </div>
      </div>
      <!-- /.progress-group -->
      <div class="progress-group">
        <span class="progress-text">
          @include('web::partials.type', ['type_id' => 16275, 'type_name' => 'Strontium'])
        </span>
        <span class="progress-number">
          <b>
            {{ number_format(optional($starbase->fuelBays->where('type_id', 16275))->first()->quantity ?? 0, 0) }}</b>/{{ number_format($starbase->strontiumBaySize / 3, 0) }}
          {{ trans_choice('web::seat.unit', 2) }}
        </span>

        <div class="progress sm">
          <div class="progress-bar progress-bar-green"
               style="width: {{ 100 * ((optional($starbase->fuelBays->where('type_id', 16275))->first()->quantity ?? 0) * 3 / $starbase->strontiumBaySize) }}%">
          </div>
        </div>
      </div>
      <!-- /.progress-group -->

      <hr>

      <dl>

        <dt>{{ trans('web::seat.sov_bonus') }}</dt>
        <dd>
          @if($starbase->system->sovereignty->alliance_id == $sheet->alliance_id || $starbase->system->sovereignty->corporation_id == $starbase->corporation_id)
            <span class="text-success">
              {{ trans('web::seat.yes') }}
            </span>
          @else
            {{ trans('web::seat.no') }}
          @endif
        </dd>

        <dt>{{ trans('web::seat.fuel_usage') }}</dt>
        <dd>
          @if($starbase->system->sovereignty->alliance_id == $sheet->alliance_id || $starbase->system->sovereignty->corporation_id == $starbase->corporation_id)
            {{ ceil($starbase->baseFuelUsage * 0.75) }}
          @else
            {{ $starbase->baseFuelUsage }}
          @endif
          {{ trans('web::seat.blocks_p_h') }}
        </dd>

        <dt>{{ trans('web::seat.stront_usage') }}</dt>
        <dd>
          @if($starbase->system->sovereignty->alliance_id == $sheet->alliance_id || $starbase->system->sovereignty->corporation_id == $starbase->corporation_id)
            {{ ceil($starbase->baseStrontiumUsage * 0.75) }}
          @else
            {{ $starbase->baseStrontiumUsage }}
          @endif

          {{ trans('web::seat.units_p_h') }}
        </dd>

        <dt>{{ trans('web::seat.offline') }}</dt>
        <dd>
          @if($starbase->system->sovereignty->alliance_id == $sheet->alliance_id || $starbase->system->sovereignty->corporation_id == $starbase->corporation_id)
            {{
              carbon('now')->addHours((optional($starbase->fuelBays->whereIn('type_id', [4051, 4246, 4247, 4312, 36945]))->first()->quantity ?? 0) / ceil($starbase->baseFuelUsage * 0.75))
                ->diffForHumans()
            }} at
            {{
              carbon('now')->addHours((optional($starbase->fuelBays->whereIn('type_id', [4051, 4246, 4247, 4312, 36945]))->first()->quantity ?? 0) / ceil($starbase->baseFuelUsage * 0.75))
            }}
          @else
            {{
              carbon('now')->addHours((optional($starbase->fuelBays->whereIn('type_id', [4051, 4246, 4247, 4312, 36945]))->first()->quantity ?? 0) / $starbase->baseFuelUsage)
                ->diffForHumans()
            }} at
            {{
              carbon('now')->addHours((optional($starbase->fuelBays->whereIn('type_id', [4051, 4246, 4247, 4312, 36945]))->first()->quantity ?? 0) / $starbase->baseFuelUsage)
            }}
          @endif
        </dd>

        <dt>{{ trans('web::seat.reinforce_estimate') }}</dt>
        <dd>
          @if($starbase->state == 'reinforced')
            {{ $starbase->reinforced_until }}
            <i>({{ human_diff($starbase->reinforced_until) }})</i>
          @else
            @if($starbase->system->sovereignty->alliance_id == $sheet->alliance_id || $starbase->system->sovereignty->corporation_id == $starbase->corporation_id)
              {{
                round((optional($starbase->fuelBays->where('type_id', 16275))->first()->quantity ?? 0) / ceil($starbase->baseStrontiumUsage * 0.75))
              }} hours at
              {{
                carbon('now')->addHours($starbase->strontium / ceil($starbase->baseStrontiumUsage * 0.75))
              }}
            @else
              {{
                round((optional($starbase->fuelBays->where('type_id', 16275))->first()->quantity ?? 0) / $starbase->baseStrontiumUsage)
              }} hours at
              {{
                carbon('now')->addHours((optional($starbase->fuelBays->where('type_id', 16275))->first()->quantity ?? 0) / $starbase->baseStrontiumUsage)
              }}
            @endif
          @endif
        </dd>

      </dl>

    </div>
  </div>

</div>
