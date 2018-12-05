@extends('web::layouts.grids.4-4-4')

@section('title', trans('web::seat.api_detail'))
@section('page_header', trans('web::seat.api_detail'))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.api_detail') }}</h3>
    </div>
    <div class="panel-body">

      <dl class="dl-horizontal">
        <dt>{{ trans('web::seat.key_id') }}</dt>
        <dd>{{ $key->key_id }}</dd>
        <dt>{{ trans('web::seat.api_key_type') }}</dt>
        <dd>
          @if($key->info)
            {{ $key->info->type }}
          @endif
        </dd>
        <dt>{{ trans('web::seat.api_access_mask') }}</dt>
        <dd>
          @if($key->info)
            {{ $key->info->accessMask }}
          @endif
        </dd>
        <dt>{{ trans('web::seat.api_key_status') }}</dt>
        <dd>
          @if($key->enabled)
            <span class="text-success">
              {{ ucfirst(trans('web::seat.enabled')) }}
            </span>
            @if (auth()->user()->has('apikey.toggle_status', false))
              <span class="pull-right">
              <a href="{{ route('api.key.disable', ['key_id' => $key->key_id]) }}" class="label label-warning">
                {{ trans('web::seat.disable') }}
              </a>
            </span>
            @endif
          @else
            <span class="text-danger">
              {{ ucfirst(trans('web::seat.disabled')) }}
            </span>
            <span class="pull-right">
              <a href="{{ route('api.key.enable', ['key_id' => $key->key_id]) }}" class="label label-primary">
                {{ trans('web::seat.re_enable') }}
              </a>
            </span>
          @endif
        </dd>
        <dt>{{ trans('web::seat.paid_until') }}</dt>
        <dd>
          @if ($key->status != null)
            <span data-toggle="tooltip" title="{{ $key->status->paidUntil }}">
            {{ human_diff($key->status->paidUntil) }}
          </span>
          @else
            <span>{{ trans('web::seat.unknown') }}</span>
          @endif
        </dd>
        <dt>{{ trans('web::seat.v_code') }}</dt>
        <dd>

          <!-- Button trigger modal -->
          <a type="button" data-toggle="modal" data-target="#vcodeModal">
            {{ trans_choice('web::seat.reveal', 1) }}
          </a>

          <!-- Modal -->
          <div class="modal fade" id="vcodeModal" tabindex="-1" role="dialog" aria-labelledby="vcodeModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                  <h4 class="modal-title" id="vcodeModalLabel">
                    {{ trans('web::seat.v_code') }}
                  </h4>
                </div>
                <div class="modal-body">

                  <p class="text-center">
                    <b>{{ $key->v_code }}</b>
                  </p>

                </div>
              </div>
            </div>
          </div>

        </dd>
      </dl>

      @if($key->enabled)
        <a href="{{ route('api.key.queue', ['key_id' => $key->key_id]) }}" class="btn btn-success btn-block">
          {{ trans('web::seat.api_job_update') }}
        </a>
      @endif

    </div>
  </div>

  {{-- if the key is disabled, show the reason --}}
  @if($key->enabled == 0)

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Last Error</h3>
      </div>
      <div class="panel-body">
        <pre>{{ $key->last_error }}</pre>
      </div>
    </div>

  @endif

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans_choice('web::seat.character', 2) }}</h3>
    </div>
    <div class="panel-body">
      <ul class="users-list clearfix">

        @foreach($key->characters as $character)
          <li>
            {!! img('character', $character->characterID, 128, ['class' => 'img-circle'])  !!}
            <a class="text-muted"
               href="{{ route('character.view.sheet', ['character_id' => $character->characterID]) }}">
              {{ $character->characterName }}
            </a>
            <span class="users-list-date">{{ $character->corporationName }}</span>
          </li>
        @endforeach

      </ul><!-- /.users-list -->
    </div>
    <div class="panel-footer">
      {{ count($key->characters) }} {{ trans_choice('web::seat.character', count($key->characters)) }}
    </div>
  </div>

  @if(auth()->user()->hasSuperUser())

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Worker Constraints</h3>
      </div>
      <div class="panel-body">

        <form role="form" action="{{ route('api.key.worker.constraints') }}" method="post">
          {{ csrf_field() }}
          <input type="hidden" name="key_id" value="{{ $key->key_id }}">

          <table class="table compact table-condensed table-hover table-responsive">
            <tbody>
            @foreach($available_workers[$key_type] as $worker => $classes)

              <tr>
                <td>{{ ucfirst($worker) }}</td>
                <td>{{ count($classes) }}</td>
                <td>

                  <div class="checkbox-inline">
                    <label>

                      @if(!is_null($current_workers) && array_key_exists($key_type, $current_workers))

                        @if(!is_null($current_workers[$key_type]) && in_array($worker, $current_workers[$key_type]))

                          <input type="checkbox" name="{{ $key_type.'[]' }}" value="{{ $worker }}" checked>

                        @else

                          <input type="checkbox" name="{{ $key_type.'[]' }}" value="{{ $worker }}">

                        @endif

                      @else

                        <input type="checkbox" name="{{ $key_type.'[]' }}" value="{{ $worker }}">

                      @endif
                      {{ trans('web::seat.enabled') }}

                    </label>
                  </div>

                </td>
              </tr>

            @endforeach

            </tbody>
          </table>

          <div class="box-footer">
            <button type="submit" class="btn btn-primary">
              {{ trans('web::seat.update') }}
            </button>
          </div>
        </form>

      </div>
    </div>

  @endif

@stop

@section('center')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.owner_info') }}</h3>
    </div>
    <div class="panel-body">

      @if(!$key->owner)
        <span class="text-muted">
          {{ trans('web::seat.no_owner') }}
        </span>
      @else
        <dl class="dl-horizontal">
          <dt>{{ trans('web::seat.username') }}</dt>
          <dd>{{ $key->owner->name }}</dd>
          <dt>{{ trans('web::seat.email') }}</dt>
          <dd>{{ $key->owner->email }}</dd>
          <dt>{{ trans('web::seat.last_login') }}</dt>
          <dd>{{ human_diff($key->owner->last_login) }}</dd>
          <dt>{{ trans('web::seat.account_status') }}</dt>
          <dd>
            @if($key->owner->active)
              <span class="label label-success">
                {{ ucfirst(trans('web::seat.enabled')) }}
              </span>
            @else
              <span class="label label-danger">
                {{ ucfirst(trans('web::seat.disabled')) }}
              </span>
            @endif
          </dd>
        </dl>
      @endif

    </div>

    @if(auth()->user()->hasSuperUser())
      <div class="panel-footer">

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#ownerModal">
          {{ trans('web::seat.transfer_ownership') }}
        </button>

        <!-- Modal -->
        <div class="modal fade" id="ownerModal" tabindex="-1" role="dialog" aria-labelledby="ownerModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                      aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('web::seat.transfer_ownership') }}</h4>
              </div>
              <div class="modal-body">

                <form role="form" action="{{ route('api.key.transfer', ['key_id' => $key->key_id]) }}" method="post">
                  {{ csrf_field() }}

                  <div class="box-body">

                    <div class="form-group">
                      <label>{{ trans('web::seat.seat_user') }}</label>
                      <select name="user_id" id="user_id" class="form-control select2" style="width: 100%;">
                      </select>
                    </div>
                    <!-- /.form-group -->

                  </div>
                  <!-- /.box-body -->

                  <div class="box-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                      {{ trans('web::seat.close') }}
                    </button>
                    <button type="submit" class="btn btn-primary pull-right">
                      {{ trans('web::seat.transfer') }}
                    </button>
                  </div>

                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.api_access') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover">
        <tbody>
        <tr>
          <th>{{ trans_choice('web::seat.name', 1) }}</th>
          <th>{{ trans('web::seat.api_min_mask') }}</th>
          <th>{{ trans('web::seat.api_access') }}</th>
        </tr>

        @if($access_map)

          @foreach($access_map as $name => $bitmask)

            <tr>
              <td>{{ ucfirst($name) }}</td>
              <td>{{ $bitmask }}</td>
              <td>
                @if($key->info->accessMask & $bitmask)
                  <span class="label label-success">
                    {{ trans('web::seat.granted') }}
                  </span>
                @else
                  <span class="label label-danger">
                    {{ trans('web::seat.denied') }}
                  </span>
                @endif
              </td>
            </tr>

          @endforeach

        @else
          <span class="text-muted">{{ trans('web::seat.mask_map_fail') }}</span>
        @endif

        </tbody>
      </table>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.recent_jobs') }}</h3>
    </div>
    <div class="panel-body">

      <p>
        {{ trans('web::seat.job_log_config',
          ['status' => config('eveapi.config.enable_joblog') ? trans('web::seat.enabled') : trans('web::seat.disabled')])
        }}
      </p>

      <a href="{{ route('api.key.joblog', ['key_id' => $key->key_id]) }}" class="btn btn-success btn-block">
        {{ trans('web::seat.joblog') }}
      </a>

      <table class="table table-condensed table-hover">
        <tbody>
        <tr>
          <th>{{ trans('web::seat.date') }}</th>
          <th>{{ trans('web::seat.scope') }}</th>
          <th>{{ trans('web::seat.status') }}</th>
        </tr>

        @foreach($jobs as $job)

          <tr>
            <td>
              <span data-toggle="tooltip" title="" data-original-title="{{ $job->created_at }}">
                {{ human_diff($job->created_at) }}
              </span>
            </td>
            <td>{{ $job->scope }}</td>
            <td>{{ $job->status }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop

@push('javascript')

  <script>

    $("#user_id").select2({
      ajax: {
        url     : "{{ route('support.api-key.userlist') }}",
        dataType: 'json',
        delay   : 250,
        data    : function (params) {
          return {
            q   : params.term, // search term
            page: params.page
          };
        }
      }
    });

  </script>

@endpush
