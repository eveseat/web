<div role="tabpanel" class="tab-pane" id="modules{{ $starbase->itemID }}">

  <div class="row">
    <div class="col-md-12">

      @if(!$starbase->modules)

        {{ trans('web::seat.no_known_assets') }}

      @else

        <table class="table table-condensed table-hover table-responsive">
          <tbody>
          <tr>
            <th>{{ trans_choice('web::seat.type', 1) }}</th>
            <th colspan="2">{{ trans('web::seat.content') }}</th>
            <th>{{ trans('web::seat.cargo_usage') }}</th>
          </tr>

          @foreach($starbase->modules as $module)

            <tr>
              <td>
                {!! img('type', $module['detail']->typeID, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                {{ $module['detail']->typeName }}
                @if($module['detail']->typeName !== $module['detail']->itemName)
                  <i>({{ $module['detail']->itemName }})</i>
                @endif
              </td>
              <td>
                @foreach($module_contents->where('parentAssetItemID', $module['detail']->itemID)->take(5) as $content)
                  <span data-toggle="tooltip"
                        title="" data-original-title="{{ $content->typeName }}">
                    {!! img('type', $content->typeID, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                  </span>
                @endforeach
              </td>
              <td>
                <b>
                  {{ number(100 * ($module['used_volume']) / ($module['available_volume']), 0) }}%
                </b>
                <i>
                  ({{ number($module['used_volume']) }} m&sup3; / {{ number($module['available_volume']) }} m&sup3;)
                  {{ number($module['total_items']) }}
                  {{ trans_choice('web::seat.item', $module['total_items']) }}
                </i>
              </td>
              <td>
                <div class="progress">
                  <div class="progress-bar" role="progressbar"
                       aria-valuenow="60" aria-valuemin="0"
                       aria-valuemax="100"
                       style="width: {{ 100 * ($module['used_volume']) / ($module['available_volume']) }}%">
                  </div>
                </div>
              </td>
              <td>

                <!-- Button trigger modal -->
                <a type="button" data-toggle="modal" data-target="#assetModal{{ $module['detail']->itemID }}">
                  <i class="fa fa-cube"></i>
                </a>

                <!-- Modal -->
                <div class="modal fade" id="assetModal{{ $module['detail']->itemID }}" tabindex="-1"
                     role="dialog" aria-labelledby="assetModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="assetModalLabel">
                          {{ trans_choice('web::seat.detail', 2) }}:
                          {{ $module['detail']->typeName }}
                        </h4>
                      </div>
                      <div class="modal-body">

                        <table class="table table-condensed table-hover table-responsive">
                          <tbody>
                          <tr>
                            <th>#</th>
                            <th></th>
                            <th>{{ trans('web::seat.volume_usage') }}</th>
                          </tr>

                          @foreach($module_contents->where('parentAssetItemID', $module['detail']->itemID) as $content)

                            <tr>
                              <td>{{ $content->quantity }}</td>
                              <td>
                                {!! img('type', $content->typeID, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                                {{ $content->typeName }}
                              </td>
                              <td>
                                {{ number(100 * ($content->quantity * $content->volume) / $module['available_volume']) }} %
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

          @endforeach

          </tbody>
        </table>

      @endif

    </div>
  </div>
</div>
