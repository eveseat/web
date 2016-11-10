@extends('web::layouts.grids.12')

@section('title', trans('web::seat.mail'))
@section('page_header', trans('web::seat.mail'))

@section('full')

  <ul class="timeline">

    <li>
      <i class="fa fa-envelope bg-blue"></i>
      <div class="timeline-item">
        <span class="time">
          <i class="fa fa-clock-o"></i>
          {{ $message->sentDate }} ({{ human_diff($message->sentDate) }})
        </span>
        <h2 class="timeline-header">
          <b>{{ trans('web::seat.from') }}: </b>
          <a href="{{ route('character.view.sheet', ['character_id' => $message->senderID]) }}">
            {!! img('character', $message->senderID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
            {{ $message->senderName }}
          </a>

    @if($message->toCorpOrAllianceID)
      <li>
        <b>{{ trans('web::seat.to_corp_alliance') }}:</b>
        {!! img('auto', $message->toCorpOrAllianceID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
        <span rel="id-to-name">{{ $message->toCorpOrAllianceID }}</span>
      </li>
    @endif

    @if($message->toCharacterIDs)
      <li>
        <b>{{ trans('web::seat.to_char') }}:</b>

        @foreach(explode(',', $message->toCharacterIDs) as $char_id)
          <a href="{{ route('character.view.sheet', ['character_id' => $char_id]) }}">
            {!! img('character', $char_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
            <span rel="id-to-name">{{ $char_id }}</span>
          </a>
        @endforeach

      </li>
      @endif

      </h2>
      <div class="timeline-body">
        {!! clean_ccp_html($message->body) !!}
      </div>

      </div>
      </li>

      <li>
        <i class="fa fa-clock-o bg-gray"></i>
      </li>
  </ul>

@stop

@section('javascript')

  @include('web::includes.javascript.id-to-name')

@stop
