@extends('web::layouts.grids.3-9')

@section('title', ucfirst(trans_choice('web::character.character', 2)))
@section('page_header', ucfirst(trans_choice('web::character.character', 2)))

@inject('request', 'Illuminate\Http\Request')

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ ucfirst(trans_choice('web::general.filter', 2)) }}</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('character.list') }}" method="get">

        <div class="box-body">

          <div class="form-group">
            <label>{{ ucfirst(trans('web::character.corporation_name')) }}</label>
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
        <!-- /.box-body -->

        <div class="box-footer">
          <a href="{{ route('character.list') }}" class="btn btn-warning pull-left">
            {{ trans('web::general.clear_filters') }}
          </a>
          <button type="submit" class="btn btn-primary pull-right">
            {{ ucfirst(trans_choice('web::general.filter', 1)) }}
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

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ ucfirst(trans_choice('web::character.character', 2)) }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover">
        <tbody>
        <tr>
          <th>{{ trans_choice('web::general.name', 1) }}</th>
          <th>{{ trans('web::character.corporation') }}</th>
          <th>{{ trans('web::character.last_location') }}</th>
        </tr>

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
            <td>{{ $character->lastKnownLocation }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      <span class="text-bold">
        {{ count($characters) }}
      </span>
        {{ ucfirst(trans_choice('web::character.character', count($characters))) }}
    </div>
  </div>

@stop

@section('javascript')

  <script>
    $("#corporationName").select2();
  </script>

@stop
