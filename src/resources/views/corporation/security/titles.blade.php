@extends('web::corporation.security.layouts.view', ['sub_viewname' => 'titles'])

@section('title', ucfirst(trans_choice('web::corporation.corporation', 1)) . ' Titles')
@section('page_header', ucfirst(trans_choice('web::corporation.corporation', 1)) . ' Titles')

@section('security_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Member Titles</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>

        @foreach($titles->unique('titleID')->groupBy('titleID') as $titleID => $data)

          <tr class="active">
            <td colspan="4">
              <b>
                  {{ strip_tags($data[0]->titleName) }}
              </b>
              <span class="pull-right">
                {{ count($titles->where('titleID', $titleID)) }}
              </span>
            </td>
          </tr>

          @foreach($titles->where('titleID', $titleID) as $character)

            <tr>
              <td>
                <a href="{{ route('character.view.sheet', ['character_id' => $character->characterID]) }}">
                  {!! img('character', $character->characterID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  {{ $character->characterName }}
                </a>
              </td>
            </tr>

          @endforeach

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
