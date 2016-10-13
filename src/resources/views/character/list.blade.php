@extends('web::layouts.grids.12')

@section('title', trans_choice('web::seat.character', 2))
@section('page_header', trans_choice('web::seat.character', 2))

@inject('request', 'Illuminate\Http\Request')

@section('full')

  <div class="row">

    <div class="col-md-12">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans_choice('web::seat.filter', 2) }}</h3>
        </div>
        <div class="panel-body">

          <form role="form" action="{{ route('character.list') }}" method="get">

            <div class="box-body">

              <div class="row">

                <div class="col-md-6">

                  <div class="form-group">
                    <label>{{ trans('web::seat.corporation_name') }}</label>
                    <select id="corporationName" class="form-control"
                            name="filter[corporationName][]" multiple>
                      @foreach($corporations as $corporation)

                        <option value="{{ $corporation }}"
                          @if(isset($request->filter['corporationName']))

                            @if(in_array($corporation, $request->filter['corporationName'])))
                              selected
                            @endif

                          @endif>
                          {{ $corporation }}
                        </option>

                      @endforeach
                    </select>
                  </div>

                </div>

                <div class="col-md-6">

                  <div class="form-group">
                    <label>{{ trans('web::seat.alliance') }}</label>
                    <select id="alliance" class="form-control"
                            name="filter[alliance][]" multiple>
                      @foreach($alliances as $alliance)

                        <option value="{{ $alliance }}"
                          @if(isset($request->filter['alliance']))

                            @if(in_array($alliance, $request->filter['alliance'])))
                              selected
                            @endif

                          @endif>
                          {{ $alliance }}
                        </option>

                      @endforeach
                    </select>
                  </div>

                </div>

              </div>

            </div>
            <!-- /.box-body -->

            <div class="box-footer">
              <a href="{{ route('character.list') }}" class="btn btn-warning pull-left">
                {{ trans('web::seat.clear') }}
              </a>
              <button type="submit" class="btn btn-primary pull-right">
                {{ ucfirst(trans_choice('web::seat.filter', 1)) }}
              </button>
            </div>
          </form>

        </div>
        @if($request->filter)
          <div class="panel-footer">
            @foreach($request->filter as $name => $filter)
              <div class="text-muted">{{ studly_case($name) }}</div>
              @foreach($filter as $detail)
              <span class="label label-default">{{ $detail }}</span>
              @endforeach
            @endforeach
          </div>
        @endif
      </div>

    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans_choice('web::seat.character', 2) }}</h3>
    </div>
    <div class="panel-body">

      <table class="table datatable compact table-condensed table-hover table-responsive">
        <thead>
          <tr>
            <th>{{ trans_choice('web::seat.name', 1) }}</th>
            <th>{{ trans_choice('web::seat.corporation', 1) }}</th>
            <th>{{ trans('web::seat.alliance') }}</th>
            <th>{{ trans('web::seat.last_location') }}</th>
          </tr>
        </thead>
        <tbody>

        @foreach($characters as $character)

          <tr>
            <td>
              <a href="{{ route('character.view.sheet', ['character_id' => $character->characterID]) }}">
                {!! img('character', $character->characterID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                {{ $character->characterName }}
              </a>
            </td>
            <td>
              {!! img('corporation', $character->corporationID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $character->corporationName }}
            </td>
            <td>
              @if($character->allianceID != null)
              {!! img('alliance', $character->allianceID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $character->alliance }}
              @endif
            </td>
            <td>{{ $character->lastKnownLocation }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop

@section('javascript')

  <script>
    $("#corporationName").select2();
    $("#alliance").select2();
  </script>

@stop
