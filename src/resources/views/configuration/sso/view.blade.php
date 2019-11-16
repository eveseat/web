@extends('web::layouts.grids.6-6')

@section('title', trans('web::seat.sso'))
@section('page_header', trans('web::seat.sso'))

@section('left')


  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">
        {{ trans('web::seat.sso_scopes') }}
      </h3>
      <div class="card-tools">
        <div class="input-group input-group-sm btn-group btn-group-sm">
          <a href="{{ route('configuration.sso.enable_all') }}" class="btn btn-sm btn-primary">
            {{ trans('web::seat.enable_all') }}
          </a>
          <a href="{{ route('configuration.sso.remove_all') }}" class="btn btn-sm btn-danger">
            {{ trans('web::seat.remove_all') }}
          </a>
        </div>
      </div>
    </div>

    <div class="card-body">

      <form role="form" action="{{ route('configuration.sso.update_scopes') }}" method="post">
        {{ csrf_field() }}

        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="available_scopes">{{ trans('web::seat.available_scopes') }}</label>
              <select name="scopes[]" id="available_scopes" class="form-control select2" style="width: 100%;"
                      multiple="multiple" data-placeholder="{{ trans('web::seat.select_item_add') }}">

                @foreach(config('eveapi.scopes') as $scope)

                  <option value="{{ $scope }}" @if (! is_null(setting('sso_scopes', true)) && in_array($scope, setting('sso_scopes', true))) selected @endif>
                    {{ $scope }}
                  </option>

                @endforeach

              </select>

            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-success btn-block">
              {{ trans('web::seat.update_sso_scopes') }}
            </button>
          </div>
        </div>

      </form>

    </div>
  </div>

@stop

@section('right')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        {{ trans('web::seat.current_configuration_status') }}
      </h3>
    </div>

    <div class="card-body">

      <dl>
        <dt>{{ trans('web::seat.client_id_status') }}</dt>
        <dd>
          @if(strlen(config('eveapi.config.eseye_client_id')) > 5)
            <p class="text-green">{{ trans('web::seat.client_id_ok') }}</p>
          @else
            <p class="text-red">{{ trans('web::seat.client_id_not_ok') }}</p>
          @endif
        </dd>

        <dt>{{ trans('web::seat.client_secret_status') }}</dt>
        <dd>
          @if(strlen(config('eveapi.config.eseye_client_secret')) > 5)
            <p class="text-green">{{ trans('web::seat.client_secret_ok') }}</p>
          @else
            <p class="text-red">{{ trans('web::seat.client_secret_not_ok') }}</p>
          @endif
        </dd>

        <dt>{{ trans('web::seat.callback_url_status') }}</dt>
        <dd>
          @if(config('eveapi.config.eseye_client_callback') == route('auth.eve.callback'))
            <p class="text-green">{{ trans('web::seat.callback_url_ok', ['url' => config('eveapi.config.eseye_client_callback')]) }}</p>
          @elseif(strlen(config('eveapi.config.eseye_client_callback')) > 5)
            <p class="text-yellow">
              {{ trans('web::seat.callback_maybe_wrong', ['current' => config('eveapi.config.eseye_client_callback'), 'suggested' => route('auth.eve.callback')]) }}
            </p>
          @else
            <p class="text-red">{{ trans('web::seat.client_callback_not_ok', ['url' => route('auth.eve.callback')]) }}</p>
          @endif
        </dd>

      </dl>

    </div>
  </div>

@stop

@push('javascript')

  @include('web::includes.javascript.id-to-name')

  <script>
    $("#available_scopes").select2();
  </script>

@endpush