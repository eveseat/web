@extends('web::corporation.security.layouts.view', ['sub_viewname' => 'roles'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.roles', 2))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.role', 2))

@section('security_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans_choice('web::seat.role', 2) }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>{{ trans_choice('web::seat.type', 1) }}</th>
          <th>{{ trans_choice('web::seat.name', 1) }}</th>
        </tr>

        @foreach($security->groupBy('character_id') as $character_id => $roles)

          <tr class="active">
            <td colspan="4">
              <b>
                <a href="{{ route('character.view.sheet', ['character_id' => $character_id]) }}">
                  {!! img('character', $character_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  <span class="id-to-name" data-id="{{ $character_id }}">{{ trans('web::seat.unknown') }}</span>
                </a>
              </b>
              <span class="pull-right">
                {{ count($security->where('character_id', $character_id)) }}
                {{ trans_choice('web::seat.role', count($security->where('character_id', $character_id))) }}
              </span>
            </td>
          </tr>

          <tr>
            <td>{{ $roles->pluck('role')->unique()->map(function($role) { return str_replace('_', ' ', $role); })->implode(', ') }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
