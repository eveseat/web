<div role="tabpanel" class="tab-pane" id="modules{{ $starbase->starbase_id }}">

  <div class="row">
    <div class="col-md-12">

      @if(!$starbase_modules)

        {{ trans('web::seat.no_known_assets') }}

      @else

        <table class="table table-condensed table-hover">
          <tbody>
          <tr>
            <th>{{ trans_choice('web::seat.type', 1) }}</th>
            <th colspan="2">{{ trans('web::seat.content') }}</th>
            <th>{{ trans('web::seat.cargo_usage') }}</th>
          </tr>

          @foreach($starbase_modules as $starbase_module)

            <tr>
              <td>
                @include('web::partials.type', ['type_id' => $starbase_module->type_id, 'type_name' => $starbase_module->type->typeName])
                @if($starbase_module->type->typeName !== $starbase_module->name)
                  <i>({{ $starbase_module->name }})</i>
                @endif
              </td>
              <td>
                @foreach($starbase_module->content as $content)
                <span data-toggle="tooltip"
                      title="" data-original-title="{{ $content->type->typeName }}">
                  {!! img('types', 'icon', $content->type_id, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                </span>
                @endforeach
              </td>
              <td>
                @if($starbase_module->type->capacity > 0)
                <b>
                  @if(is_null($starbase_module->content))
                  0%
                  @else
                  {{ number_format($starbase_module->usedVolumeRate, 2) }}%
                  @endif
                </b>
                <i>
                  ({{ number_format($starbase_module->usedVolume) }} m&sup3; / {{ number_format($starbase_module->type->capacity) }} m&sup3;)
                  {{ number_format($starbase_module->content->count(), 0) }}
                  {{ trans_choice('web::seat.item', $starbase_module->content->count()) }}
                </i>
                @endif
              </td>
              <td>
                @if($starbase_module->type->capacity > 0)
                @include('web::macros.progressbar',
                  ['partial' => $starbase_module->usedVolume,'full' => $starbase_module->type->capacity])
                @else
                {{ trans('web::seat.no_storage') }}
                @endif
              </td>
              <td>
                {{-- TODO : use an ajax call to load the modal content and generate the modal only once instead for all row --}}
                @if($starbase_module->content->count() > 0)

                <!-- Button trigger modal -->
                <a type="button" data-widget="modal" data-target="#assetModal{{ $starbase_module->item_id }}">
                  <i class="fas fa-cube"></i>
                </a>

                <!-- Modal -->
                <div class="modal fade" id="assetModal{{ $starbase_module->item_id }}" tabindex="-1"
                     role="dialog" aria-labelledby="assetModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="assetModalLabel">
                          {{ trans_choice('web::seat.detail', 2) }}:
                          {{ $starbase_module->type->typeName }}
                        </h4>
                      </div>
                      <div class="modal-body">

                        <table class="table table-sm table-condensed table-hover">
                          <tbody>
                          <tr>
                            <th>#</th>
                            <th>{{ trans_choice('web::seat.type', 0) }}</th>
                            <th>{{ trans('web::seat.volume_usage') }}</th>
                          </tr>

                          @foreach($starbase_module->content as $content)

                            <tr>
                              <td>{{ $content->quantity }}</td>
                              <td>
                                @include('web::partials.type', ['type_id' => $content->type_id, 'type_name' => $content->type->typeName])
                              </td>
                              <td>
                                @include('web::macros.progressbar',
                                  ['partial' => $content->quantity * $content->type->volume,'full' => $starbase_module->type->capacity])
                              </td>
                            </tr>

                          @endforeach

                          </tbody>
                        </table>

                      </div>
                    </div>
                  </div>
                </div>

                @endif

              </td>
            </tr>

          @endforeach

          </tbody>
        </table>

      @endif

    </div>
  </div>
</div>
