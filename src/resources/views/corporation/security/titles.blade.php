@extends('web::corporation.security.layouts.view', ['sub_viewname' => 'titles', 'breadcrumb' => trans_choice('web::seat.title', 2)])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.title', 2))

@section('security_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans_choice('web::seat.title', 2) }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>

        @foreach($titles as $title)

          <tr class="active">
            <td colspan="4">
              <b>
                {{ strip_tags($title->name) }}
              </b>
              <span class="pull-right">
                {{ $title->characters->count() }}
                {{ trans_choice('web::seat.character', $title->characters->count()) }}
              </span>
            </td>
          </tr>

          @foreach($title->characters as $member)

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
