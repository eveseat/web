<div class="panel panel-default" id="starbaseDetail{{ $starbase->itemID }}">
  <div class="panel-heading">
    <h3 class="panel-title">
      {{ $starbase->starbaseName }} <i>({{ $starbase->moonName }})</i>
    </h3>
  </div>
  <div class="panel-body">

    <div>

      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
          <a href="#status{{ $starbase->itemID }}" aria-controls="status{{ $starbase->itemID }}"
             role="tab" data-toggle="tab">Status</a>
        </li>
        <li role="presentation">
          <a href="#modules{{ $starbase->itemID }}" aria-controls="modules{{ $starbase->itemID }}"
             role="tab" data-toggle="tab">{{ trans_choice('web::seat.module', 2) }}</a>
        </li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="status{{ $starbase->itemID }}">

          <div class="row">
            <div class="col-md-8">

              <dl class="dl-horizontal">

                <dt>{{ trans('web::seat.state') }}:</dt>
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
                  @if($starbase->allowCorporationMembersn)
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
                       style="width: {{ 100 * (($starbase->fuelBlocks * 5)/$starbase->fuelBaySize) }}%"></div>
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
                       style="width: {{ 100 * (($starbase->strontium * 3)/$starbase->strontBaySize) }}%"></div>
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
                    {{ $starbase->baseFuelUsage * 0.75 }}
                  @else
                    {{ $starbase->baseFuelUsage }}
                  @endif
                  {{ trans('web::seat.blocks_p_h') }}
                </dd>

                <dt>Strontium {{ trans('web::seat.usage') }}</dt>
                <dd>
                  @if($starbase->inSovSystem)
                    {{ $starbase->baseStrontUsage * 0.75}}
                  @else
                    {{ $starbase->baseStrontUsage }}
                  @endif

                  {{ trans('web::seat.units_p_h') }}
                </dd>

                <dt>{{ trans('web::seat.offline') }}</dt>
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

                <dt>{{ trans('web::seat.reinforce_estimate') }}</dt>
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
        <div role="tabpanel" class="tab-pane" id="modules{{ $starbase->itemID }}">

          <div class="row">
            <div class="col-md-12">

              <table class="table table-condensed table-hover table-responsive">
                <tbody>
                <tr>
                  <th>{{ trans_choice('web::seat.type', 1) }}</th>
                  <th colspan="2">{{ trans('web::seat.content') }}</th>
                  <th>{{ trans('web::seat.cargo_usage') }}</th>
                </tr>

                @forelse($assetlist_locations->get($starbase->moonID) as $asset)

                  <tr>
                    <td>
                      {!! img('type', $asset->typeID, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                      {{ $asset->typeName }}
                      @if($asset->typeName !== $asset->itemName)
                        <i>({{ $asset->itemName }})</i>
                      @endif
                    </td>
                    <td>
                      @foreach($asset_contents->where('parentAssetItemID', $asset->itemID)->take(5) as $content)
                        <span data-toggle="tooltip"
                              title="" data-original-title="{{ $content->typeName }}">
                          {!! img('type', $content->typeID, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                        </span>
                      @endforeach
                    </td>
                    <td>
                      <b>{{ number(100 * (
                            $asset_contents->where('parentAssetItemID', $asset->itemID)->sum(function($_) {
                              return $_->quantity * $_->volume;
                            })/
                            (in_array($asset->typeID, $cargo_types_with_bonus) ?
                              $asset->capacity*(1 + $starbase->siloCapacityBonus / 100) : $asset->capacity)))
                         }}%
                      </b>
                      <i>({{ number($asset_contents->where('parentAssetItemID', $asset->itemID)->sum(function($_) {
                                return $_->quantity * $_->volume;
                              })) }}  m&sup3; /
                        {{
                          number(in_array($asset->typeID, $cargo_types_with_bonus) ?
                            $asset->capacity*(1 + $starbase->siloCapacityBonus / 100) : $asset->capacity)
                        }} m&sup3;)
                        {{ number($asset_contents->where('parentAssetItemID', $asset->itemID)->sum('quantity'), 0) }}
                        {{ trans_choice('web::seat.item', $asset_contents->where('parentAssetItemID', $asset->itemID)->sum('quantity')) }}
                      </i>
                    </td>
                    <td>
                      <div class="progress">
                        <div class="progress-bar" role="progressbar"
                             aria-valuenow="60" aria-valuemin="0"
                             aria-valuemax="100"
                             style="width: {{
                                100 * (
                                  $asset_contents->where('parentAssetItemID', $asset->itemID)->sum(function($_) {
                                    return $_->quantity * $_->volume;
                                  })/
                                  (in_array($asset->typeID, $cargo_types_with_bonus) ?
                                    $asset->capacity*(1 + $starbase->siloCapacityBonus / 100) : $asset->capacity))
                             }}%">
                        </div>
                      </div>
                    </td>
                    <td>

                      <!-- Button trigger modal -->
                      <a type="button" data-toggle="modal" data-target="#assetModal{{ $asset->itemID }}">
                        <i class="fa fa-cube"></i>
                      </a>

                      <!-- Modal -->
                      <div class="modal fade" id="assetModal{{ $asset->itemID }}" tabindex="-1"
                           role="dialog" aria-labelledby="assetModalLabel">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                              <h4 class="modal-title" id="assetModalLabel">
                                {{ trans_choice('web::seat.detail', 2) }}:
                                {{ $asset->typeName }}
                              </h4>
                            </div>
                            <div class="modal-body">

                              <table class="table table-condensed table-hover table-responsive">
                                <tbody>
                                <tr>
                                  <th>#</th>
                                  <th></th>
                                  <th></th>
                                </tr>

                                @foreach($asset_contents->where('parentAssetItemID', $asset->itemID) as $content)

                                  <tr>
                                    <td>{{ $content->quantity }}</td>
                                    <td>
                                      {!! img('type', $content->typeID, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                                      {{ $content->typeName }}
                                    </td>
                                    <td>
                                      {{
                                        number(100 * (($content->volume * $content->quantity)/
                                          (in_array($asset->typeID, $cargo_types_with_bonus) ?
                                            $asset->capacity*(1 + $starbase->siloCapacityBonus / 100) : $asset->capacity)))
                                      }}%
                                    </td>
                                  </tr>

                                @endforeach

                                </tbody>
                              </table>

                            </div>
                          </div>
                        </div>
                      </div>

                    </td>
                  </tr>

                @empty

                  {{ trans('web::seat.no_known_assets') }}

                @endforelse

                </tbody>
              </table>

            </div>

          </div>

        </div>
      </div>

    </div>

  </div>
</div>
