@extends('web::layouts.grids.12')

@section('title', trans('web::character.mail_timeline'))
@section('page_header', trans('web::character.mail_timeline'))

@section('full')

  <ul class="timeline">

    @foreach(
      $messages->groupBy(function($message) {
        return carbon($message->sentDate)->format('l jS \\of F Y');
      }) as $day => $digest
    )
      <li class="time-label">
        <span class="bg-blue">
          {{ $day }}
        </span>
      </li>


      @foreach($digest as $message)

        <li>
          <i class="fa fa-envelope bg-blue"></i>
          <div class="timeline-item">
            <span class="time">
              <i class="fa fa-clock-o"></i>
              {{ $message->sentDate }} ({{ human_diff($message->sentDate) }})
            </span>
            <h3 class="timeline-header">
              <b>From: </b>
              <a href="{{ route('character.view.sheet', ['character_id' => $message->senderID]) }}">
                {!! img('character', $message->senderID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                {{ $message->senderName }}
              </a>
            </h3>
            <div class="timeline-body">
              {!! clean_ccp_html($message->body) !!}
            </div>
            <div class="timeline-footer">
              <a class="btn btn-primary btn-xs">Read more</a>
            </div>
          </div>
        </li>

      @endforeach

    @endforeach

    <li>
      <i class="fa fa-clock-o bg-gray"></i>
    </li>
  </ul>

  {!! $messages->render() !!}

@stop
