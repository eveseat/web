@extends('web::corporation.security.layouts.view', ['sub_viewname' => 'titles', 'breadcrumb' => trans_choice('web::seat.title', 2)])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.title', 2))

@section('security_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans_choice('web::seat.title', 2) }}</h3>
    </div>
    <div class="card-body p-0">

      <table class="table table-condensed table-hover">
        <tbody>

        @foreach($titles as $title)

          <tr class="bg-light">
            <td>
              <b>
                {{ strip_tags($title->name) }}
              </b>
              <span class="float-right">
                {{ $title->characters->count() }}
                {{ trans_choice('web::seat.character', $title->characters->count()) }}
              </span>
            </td>
          </tr>

          @foreach($title->characters as $member)

            <tr>
              <td>
                <a href="{{ route('seatcore::character.view.sheet', ['character' => $member->character_id]) }}">
                  {!! img('characters', 'portrait', $member->character_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
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
