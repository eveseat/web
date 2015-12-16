@extends('web::corporation.security.layouts.view', ['sub_viewname' => 'titles'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.title', 2))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.title', 2))

@section('security_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans_choice('web::seat.title', 2) }}</h3>
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
                {{ trans_choice('web::seat.character', count($titles->where('titleID', $titleID))) }}
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
