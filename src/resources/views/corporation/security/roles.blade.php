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

        @foreach($security->unique('characterName')->groupBy('characterName') as $character_name => $data)

          <tr class="active">
            <td colspan="4">
              <b>
                <a href="{{ route('character.view.sheet', ['character_id' => $data[0]->characterID]) }}">
                  {!! img('character', $data[0]->characterID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  {{ $character_name }}
                </a>
              </b>
              <span class="pull-right">
                {{ count($security->where('characterName', $character_name)) }}
                {{ trans_choice('web::seat.role', count($security->where('characterName', $character_name))) }}
              </span>
            </td>
          </tr>

          @foreach($security->where('characterName', $character_name) as $character)

            <tr>
              <td>{{ ucfirst($character->roleType) }}</td>
              <td>{{ ucfirst($character->roleName) }}</td>
            </tr>

          @endforeach

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
