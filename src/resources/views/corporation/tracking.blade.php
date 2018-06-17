@extends('web::corporation.layouts.view', ['viewname' => 'tracking'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.tracking'))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.tracking'))

@section('corporation_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.tracking') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table datatable compact table-condensed table-hover table-responsive">
        <thead>
        <tr>
          <th>{{ trans_choice('web::seat.name', 1) }}</th>
          <th>{{ trans('web::seat.joined') }}</th>
          <th>{{ trans('web::seat.location') }}</th>
          <th>{{ trans('web::seat.last_login') }}</th>
          <th>{{ trans('web::seat.token') }}</th>
        </tr>
        </thead>
        <tbody>

        @foreach($tracking as $character)

          <tr>
            <td>
              <a href="{{ route('character.view.sheet', ['character_id' => $character->character_id]) }}">
                {!! img('character', $character->character_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                <span rel="id-to-name">{{ $character->character_id }}</span>
              </a>
            </td>
            <td data-order="{{ $character->start_date }}">
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $character->start_date }}">
                {{ human_diff($character->start_date) }}
              </span>
            </td>
            <td>
              {{ $character->location_id }}
              @if(!is_null($character->ship_type_id))
                <i class="pull-right" data-toggle="tooltip"
                   title="" data-original-title="{{ $character->type->typeName }}">
                  {!! img('type', $character->ship_type_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                </i>
              @endif
            </td>
            <td data-order="{{ $character->logon_date }}">
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $character->logon_date }}">
                {{ human_diff($character->logon_date) }}
              </span>
            </td>
            <td data-order="{{ $character->key_ok }}">
              @if($character->key_ok == 1)
                <i class="fa fa-check text-success"></i>
              @else
                <i class="fa fa-exclamation-triangle text-danger"></i>
              @endif
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">Registered users <b>{{ $tracking->where('key_ok', true)->count() }} / {{ $tracking->count() }}</b></div>
  </div>

@stop
