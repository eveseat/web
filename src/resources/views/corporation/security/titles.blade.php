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

        @foreach($titles->groupBy('name') as $title_name => $members)

          <tr class="active">
            <td colspan="4">
              <b>
                {{ strip_tags($title_name) }}
              </b>
              <span class="pull-right">
                {{ count($members) }}
                {{ trans_choice('web::seat.character', count($members)) }}
              </span>
            </td>
          </tr>

          @foreach($members as $member)

            <tr>
              <td>
                <a href="{{ route('character.view.sheet', ['character_id' => $member->character_id]) }}">
                  {!! img('character', $member->character_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  <span class="id-to-name" data-id="{{ $member->character_id }}">{{ trans('web::seat.unknown') }}</span>
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
