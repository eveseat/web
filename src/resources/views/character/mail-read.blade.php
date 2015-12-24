@extends('web::character.layouts.view', ['viewname' => 'mail'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.mail'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.mail'))

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.mail') }}</h3>
    </div>
    <div class="panel-body">

      <p>
        <h4>
          <i class="fa fa-envelope-o"></i>
          {{ $message->title }}
        </h4>
      </p>

      <p>
        <ul class="list-unstyled">
          <li>
            <b>Sent:</b>
            {{ $message->sentDate }} ({{ human_diff($message->sentDate) }})
          </li>
          <li>
            <b>From:</b>
            <a href="{{ route('character.view.sheet', ['character_id' => $message->senderID]) }}">
              {!! img('character', $message->senderID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $message->senderName }}
            </a>
          </li>

          @if($message->toCorpOrAllianceID)
            <li>
              <b>To Corporation / Alliance:</b>
              {!! img('auto', $message->toCorpOrAllianceID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              <span rel="id-to-name">{{ $message->toCorpOrAllianceID }}</span>
            </li>
          @endif

          @if($message->toCharacterIDs)
            <li>
              <b>To Characters:</b>

              @foreach(explode(',', $message->toCharacterIDs) as $char_id)
                <a href="{{ route('character.view.sheet', ['character_id' => $char_id]) }}">
                  {!! img('character', $char_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  <span rel="id-to-name">{{ $char_id }}</span>
                </a>
              @endforeach

            </li>
          @endif

        </ul>
      </p>

      <hr>

      <p>
        {!! clean_ccp_html($message->body) !!}
      </p>

    </div>
  </div>

@stop

@section('javascript')

  @include('web::includes.javascript.id-to-name')

@stop
