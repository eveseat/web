  <div class="row">
    <div class="col-md-12">

              <table class="table datatable compact table-condensed table-hover table-responsive dataTable no-footer" id="silo_table">
                <thead>
                <tr>
                    <!-- @todo - setup new words in language files -->
                  <th>{{ trans_choice('web::seat.type', 1) }}</th>
                  <th tabindex="0" aria-controls="silo_table" rowspan="1" colspan="1" aria-label="Location: activate to sort column ascending">{{ trans_choice('web::seat.location', 1) }}</th>
                  <th>Type</th>
                  <th>Content</th>
                  <th>
                    {!! img('type', 4051, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                    Cargo Usage
                    <!-- @todo - setup new words in language files -->
                  </th>
                  <th></th>
                </tr>
                </thead>
                <tbody>


                @foreach($starbases as $key => $starbase)

                  @foreach($starbase->modules as $module)

                  <tr>
                    <td>
                      {!! img('type', $module['detail']->typeID, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                      {{ $module['detail']->typeName }}
                      @if($module['detail']->typeName !== $module['detail']->itemName)
                        <i>({{ $module['detail']->itemName }})</i>
                      @endif
                    </td>
                    <td data-order="{{ $starbase->moonName }}">
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

                      <span data-toggle="tooltip" title="" data-original-title="{{ $starbase->starbaseTypeName }}">
                        {!! img('type', $starbase->starbaseTypeID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                        {{ $starbase->starbaseName }}
                      </span>
                    </td>
                    <td>
                      <b>
                        @if($module['used_volume'] == 0 || $module['available_volume'] == 0)
                          0%
                        @else
                          {{ number(100 * ($module['used_volume']) / ($module['available_volume']), 0) }}%
                        @endif
                      </b>
                      <i>
                        ({{ number($module['used_volume']) }} m&sup3; / {{ number($module['available_volume']) }} m&sup3;)
                        {{ number($module['total_items']) }}
                        {{ trans_choice('web::seat.item', $module['total_items']) }}
                      </i>
                    </td>
                    <td>
                      @include('web::macros.progressbar',
                        ['partial' => $module['used_volume'],'full' => $module['available_volume']])
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



                                  <tr>
                                    <td>{{ $module["detail"]->quantity }}</td>
                                    <td>
                                      {!! img('type', $module["detail"]->typeID, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                                      {{ $module["detail"]->typeName }}
                                    </td>
                                    <td>
                                      @include('web::macros.progressbar',
                                        ['partial' => $module["detail"]->quantity * $module["detail"]->volume,'full' => $module['available_volume']])
                                    </td>
                                  </tr>



                                </tbody>
                              </table>

                            </div>
                          </div>
                        </div>
                      </div>

                    </td>
                  </tr>

                  @endforeach



                @endforeach

                </tbody>
              </table>

    </div>
  </div>
