@extends('web::layouts.grids.12')

@section('title', trans('web::api.add'))
@section('page_header', trans('web::api.add'))

@section('full')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">All Api Keys</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover">
        <tbody>
        <tr>
          <th>{{ ucfirst(trans_choice('web::general.id', 1)) }}</th>
          <th>{{ ucfirst(trans_choice('web::general.type', 1)) }}</th>
          <th>{{ ucfirst(trans('web::general.expire')) }}</th>
          <th>{{ ucfirst(trans_choice('web::character.character', 1)) }}</th>
          <th></th>
        </tr>

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
                {{ ucfirst(trans('web::general.never')) }}
              @endif
            </td>
            <td>
              @if($key->info)
                @if($key->info->expires))
                  {{ human_diff($key->info->expires) }}
                @else
                  {{ ucfirst(trans('web::general.never')) }}
                @endif
              @else
                {{ ucfirst(trans('web::general.unknown')) }}
              @endif
            </td>
            <td>
              @foreach($key->characters as $character)
                {!! img('character', $character->characterID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
                {{ $character->characterName }}
              @endforeach
            </td>
            <td>
              <div class="btn-group">
                <a href="#" type="button" class="btn btn-primary btn-xs">
                  {{ ucfirst(trans('web::general.edit')) }}
                </a>
                <a href="{{ route('api.key.delete', ['key_id' => $key->key_id]) }}" type="button" class="btn btn-danger btn-xs confirmlink">
                  {{ ucfirst(trans('web::general.delete')) }}
                </a>
              </div>
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      <b>{{ count($keys) }}</b> {{ trans_choice('web::general.key', count($keys)) }}
    </div>
  </div>

@stop
