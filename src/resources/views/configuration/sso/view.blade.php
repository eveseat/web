@extends('web::layouts.grids.6-6')

@section('title', trans('web::seat.sso'))
@section('page_header', trans('web::seat.sso'))

@section('left')


  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans('web::seat.sso_scopes') }}

        <span class="pull-right">
          <a href="{{ route('configuration.sso.remove_all') }}" class="btn btn-xs btn-danger">
            {{ trans('web::seat.remove_all') }}
          </a>
          <a href="{{ route('configuration.sso.enable_all') }}" class="btn btn-xs btn-primary">
            {{ trans('web::seat.enable_all') }}
          </a>
        </span>

      </h3>
    </div>

    <div class="panel-body">

      <form role="form" action="{{ route('configuration.sso.update_scopes') }}" method="post">
        {{ csrf_field() }}

        <div class="form-group">
          <label for="scopes">{{ trans('web::seat.available_scopes') }}</label>
          <select name="scopes[]" id="available_scopes" style="width: 100%" multiple>

            @foreach(config('eveapi.scopes') as $scope)

              <option value="{{ $scope }}" @if (! is_null(setting('sso_scopes', true)) && in_array($scope, setting('sso_scopes', true))) selected @endif>
                {{ $scope }}
              </option>

            @endforeach

          </select>

        </div>

        <button type="submit" class="btn btn-success btn-block">
          {{ trans('web::seat.update_sso_scopes') }}
        </button>

      </form>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans('web::seat.current_configuration_status') }}

      </h3>
    </div>

    <div class="panel-body">

      <dl>
        <dt>{{ trans('web::seat.client_id_status') }}</dt>
        <dd>
          @if(strlen(env('EVE_CLIENT_ID')) > 5)
            <p class="text-green">{{ trans('web::seat.client_id_ok') }}</p>
          @else
            <p class="text-red">{{ trans('web::seat.client_id_not_ok') }}</p>
          @endif
        </dd>

        <dt>{{ trans('web::seat.client_secret_status') }}</dt>
        <dd>
          @if(strlen(env('EVE_CLIENT_SECRET')) > 5)
            <p class="text-green">{{ trans('web::seat.client_secret_ok') }}</p>
          @else
            <p class="text-red">{{ trans('web::seat.client_secret_not_ok') }}</p>
          @endif
        </dd>

        <dt>{{ trans('web::seat.callback_url_status') }}</dt>
        <dd>
          @if(env('EVE_CALLBACK_URL') == route('auth.eve.callback'))
            <p class="text-green">{{ trans('web::seat.callback_url_ok', ['url' => env('EVE_CALLBACK_URL')]) }}</p>
          @elseif(strlen(env('EVE_CALLBACK_URL')) > 5)
            <p class="text-yellow">
              {{ trans('web::seat.callback_maybe_wrong', ['current' => env('EVE_CALLBACK_URL'), 'suggested' => route('auth.eve.callback')]) }}
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
    $("#available_scopes").select2({
      placeholder: "{{ trans('web::seat.select_item_add') }}"
    });
  </script>

@endpush