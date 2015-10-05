@extends('web::layouts.grids.4-4-4')

@section('title', trans('web::api.detail'))
@section('page_header', trans('web::api.detail'))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::api.detail') }}</h3>
    </div>
    <div class="panel-body">

      <dl class="dl-horizontal">
        <dt>{{ trans('web::api.key_id') }}</dt>
        <dd>{{ $key->key_id }}</dd>
        <dt>{{ trans('web::api.key_type') }}</dt>
        <dd>{{ $key->info->type }}</dd>
        <dt>{{ trans('web::api.access_mask') }}</dt>
        <dd>{{ $key->info->accessMask }}</dd>
        <dt>{{ trans('web::api.key_status') }}</dt>
        <dd>
          @if($key->enabled)
            <span class="label label-success">
              {{ ucfirst(trans('web::general.enabled')) }}
            </span>
          @else
            <span class="label label-danger">
              {{ ucfirst(trans('web::general.disabled')) }}
            </span>
          @endif
        </dd>
      </dl>

    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ ucfirst(trans_choice('web::character.character', 2)) }}</h3>
    </div>
    <div class="panel-body">

      <ul class="users-list clearfix">

        @foreach($key->characters as $character)
          <li>
            {!! img('character', $character->characterID, 128, ['class' => 'img-circle'])  !!}
            <a class="text-muted" href="#">{{ $character->characterName }}</a>
            <span class="users-list-date">{{ $character->corporationName }}</span>
          </li>
        @endforeach

      </ul><!-- /.users-list -->

    </div>
    <div class="panel-footer">
      {{ count($key->characters) }} {{ trans_choice('web::character.character', count($key->characters)) }}
    </div>
  </div>

  <a href="{{ route('api.key.queue', ['key_id' => $key->key_id]) }}" class="btn btn-success btn-block">
    {{ trans('web::api.job_update') }}
  </a>
@stop

@section('center')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::api.owner_info') }}</h3>
    </div>
    <div class="panel-body">

      @if(!$key->owner)
        <span class="text-muted">
          {{ trans('web::api.no_owner') }}
        </span>
      @else
        <dl class="dl-horizontal">
          <dt>{{ ucfirst(trans_choice('web::general.username', 1)) }}</dt>
          <dd>{{ $key->owner->name }}</dd>
          <dt>{{ ucfirst(trans_choice('web::general.email', 1)) }}</dt>
          <dd>{{ $key->owner->email }}</dd>
          <dt>{{ trans('web::access.last_login') }}</dt>
          <dd>{{ human_diff($key->owner->last_login) }}</dd>
          <dt>Account Status</dt>
          <dd>
            @if($key->owner->active)
              <span class="label label-success">
                {{ ucfirst(trans('web::general.enabled')) }}
              </span>
            @else
              <span class="label label-danger">
                {{ ucfirst(trans('web::general.disabled')) }}
              </span>
            @endif
          </dd>
        </dl>
      @endif

    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::api.mask_breakdown') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover">
        <tbody>
        <tr>
          <th>{{ ucfirst(trans_choice('web::general.name', 1)) }}</th>
          <th>{{ trans('web::api.min_mask') }}</th>
          <th>{{ trans('web::api.access') }}</th>
        </tr>

        @foreach($access_map as $name => $bitmask)

          <tr>
            <td>{{ ucfirst($name) }}</td>
            <td>{{ $bitmask }}</td>
            <td>
              @if($key->info->accessMask & $bitmask)
                <span class="label label-success">
                      {{ trans('web::api.granted') }}
                    </span>
              @else
                <span class="label label-danger">
                    {{ trans('web::api.denied') }}
                    </span>
              @endif
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::api.recent_jobs') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover">
        <tbody>
        <tr>
          <th>{{ trans('web::api.scheduled') }}</th>
          <th>{{ trans('web::api.scope') }}</th>
          <th>{{ ucfirst(trans_choice('web::general.status', 1)) }}</th>
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
