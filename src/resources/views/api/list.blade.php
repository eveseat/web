@extends('web::layouts.grids.12')

@section('title', trans('web::seat.api_all'))
@section('page_header', trans('web::seat.api_all'))

@section('full')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.api_all') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table datatable compact table-condensed table-hover table-responsive">
        <thead>
          <tr>
            <th>{{ trans_choice('web::seat.id', 1) }}</th>
            <th>{{ trans_choice('web::seat.type', 1) }}</th>
            <th>{{ trans('web::seat.expiry') }}</th>
            <th data-orderable="false">{{ trans_choice('web::seat.character', 1) }}</th>
            <th data-orderable="false"></th>
          </tr>
        </thead>
        <tbody>

        @foreach($keys as $key)

          <tr @if(!$key->enabled)class="danger"@endif>
            <td>
              @if(!$key->enabled)
                <i class="fa fa-exclamation-triangle text-danger"
                   data-toggle="tooltip" title="" data-original-title="{{ $key->last_error }}"></i>
              @endif
              {{ $key->key_id }}
            </td>
            <td>
              @if($key->info)
                {{ $key->info->type }}
              @else
                {{ trans('web::seat.never') }}
              @endif
            </td>
            <td>
              @if($key->info)
                @if($key->info->expires)
                  {{ human_diff($key->info->expires) }}
                @else
                  {{ trans('web::seat.never') }}
                @endif
              @else
                {{ trans('web::seat.unknown') }}
              @endif
            </td>
            <td>
              @foreach($key->characters as $character)
                <a href="{{ route('character.view.sheet', ['character_id' => $character->characterID]) }}">
                  {!! img('character', $character->characterID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
                  {{ $character->characterName }}
                </a>
              @endforeach
            </td>
            <td>
              <div class="btn-group">
                <a href="{{ route('api.key.detail', ['key_id' => $key->key_id]) }}" type="button"
                   class="btn btn-primary btn-xs col-xs-6">
                  {{ trans_choice('web::seat.detail', 2) }}
                </a>
                <a href="{{ route('api.key.delete', ['key_id' => $key->key_id]) }}" type="button"
                   class="btn btn-danger btn-xs confirmlink col-xs-6">
                  {{ trans('web::seat.delete') }}
                </a>
              </div>
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
