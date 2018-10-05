@extends('web::character.layouts.view', ['viewname' => 'mail'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.mail'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.mail'))

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.mail') }}</h3>
    </div>
    <div class="panel-body">

      <h4>
        <i class="fa fa-envelope-o"></i>
        {{ $message->subject }}
      </h4>

      <p>
      <ul class="list-unstyled">
        <li>
          <b>Sent:</b>
          {{ $message->timestamp }} ({{ human_diff($message->timestamp) }})
        </li>
        <li>
          <b>From:</b>
          <a href="{{ route('character.view.sheet', ['character_id' => $message->from]) }}">
            {!! img('character', $message->from, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
            <span class="id-to-name" data-id="{{ $message->from }}">{{ trans('web::seat.unknown') }}</span>
          </a>
        </li>

        @if($message->recipients->where('recipient_type', 'alliance')->count() > 0)
          <li>
            <b>To Alliance:</b>
            @foreach($message->recipients->where('recipient_type', 'alliance') as $alliance)
              {!! img('alliance', $alliance->recipient_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              <span class="id-to-name" data-id="{{ $alliance->recipient_id }}">{{ trans('web::seat.unknown') }}</span>
            @endforeach
          </li>
        @endif

        @if($message->recipients->where('recipient_type', 'corporation')->count() > 0)
          <li>
            <b>To Corporation:</b>
            @foreach($message->recipients->where('recipient_type', 'corporation') as $corporation)
              {!! img('corporation', $corporation->recipient_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              <span class="id-to-name"
                    data-id="{{ $corporation->recipient_id }}">{{ trans('web::seat.unknown') }}</span>
            @endforeach
          </li>
        @endif

        @if($message->recipients->where('recipient_type', 'character')->count() > 0)
          <li>
            <b>To Characters:</b>

            @foreach($message->recipients->where('recipient_type', 'character') as $character)
              <a href="{{ route('character.view.sheet', ['character_id' => $character->recipient_id]) }}">
                {!! img('character', $character->recipient_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                <span class="id-to-name"
                      data-id="{{ $character->recipient_id }}">{{ trans('web::seat.unknown') }}</span>
              </a>
            @endforeach

          </li>
        @endif

      </ul>
      </p>

      <p>

        @if(setting('mail_threads') == "yes")

          @include('web::character.partials.messagethread')

        @else

          {!! clean_ccp_html($message->body->body) !!}

        @endif

      </p>

    </div>
  </div>

@stop

@push('javascript')

  @include('web::includes.javascript.id-to-name')

@endpush
