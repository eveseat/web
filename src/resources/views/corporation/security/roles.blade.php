@extends('web::corporation.security.layouts.view', ['sub_viewname' => 'roles'])

@section('title', ucfirst(trans_choice('web::corporation.corporation', 1)) . ' Security')
@section('page_header', ucfirst(trans_choice('web::corporation.corporation', 1)) . ' Security')

@section('security_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Character Roles</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>Role Type</th>
          <th>Role Name</th>
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
                {{ count($security->where('characterName', $character_name)) }} roles
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
