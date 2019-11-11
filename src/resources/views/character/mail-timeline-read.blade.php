@extends('web::layouts.grids.12')

@section('title', trans('web::seat.mail'))
@section('page_header', trans('web::seat.mail'))

@section('full')

  <div class="timeline">

    <div>
      <i class="fas fa-envelope bg-blue"></i>

      <div class="timeline-item">
        <span class="time">
          <i class="fas fa-clock"></i>
          {{ $message->timestamp }} ({{ human_diff($message->timestamp) }})
        </span>
        <h3 class="timeline-header">
          <ul class="list-unstyled">
            <li>
              <b>{{ trans('web::seat.from') }}: </b>
              <a href="{{ route('character.view.sheet', ['character_id' => $message->from]) }}">
                {!! img('characters', 'portrait', $message->from, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                <span class="id-to-name" data-id="{{ $message->from }}">{{ trans('web::seat.unknown') }}</span>
              </a>
            </li>

            @if($message->recipients->where('recipient_type', 'alliance')->count() > 0)
              <li>
                <b>{{ trans('web::seat.to_alliance') }}:</b>

                @foreach($message->recipients->where('recipient_type', 'alliance') as $recipient)

                  {!! img('alliances', 'logo', $recipient->recipient_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  <span class="id-to-name" data-id="{{ $recipient->recipient_id }}">{{ trans('web::seat.unknown') }}</span>

                @endforeach

              </li>
            @endif

            @if($message->recipients->where('recipient_type', 'corporation')->count() > 0)
              <li>
                <b>{{ trans('web::seat.to_corp') }}:</b>

                @foreach($message->recipients->where('recipient_type', 'corporation') as $recipient)

                  {!! img('corporations', 'logo', $recipient->recipient_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  <span class="id-to-name" data-id="{{ $recipient->recipient_id }}">{{ trans('web::seat.unknown') }}</span>

                @endforeach
              </li>
            @endif

            @if($message->recipients->where('recipient_type', 'character')->count() > 0)
              <li>
                <b>{{ trans('web::seat.to_char') }}:</b>

                @foreach($message->recipients->where('recipient_type', 'character') as $recipient)

                  {!! img('characters', 'portrait', $recipient->recipient_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  <span class="id-to-name" data-id="{{ $recipient->recipient_id }}">{{ trans('web::seat.unknown') }}</span>

                @endforeach

              </li>
            @endif

          </ul>
        </h3>
        <div class="timeline-body">

          @if(setting('mail_threads') == "yes")

            @include('web::character.partials.messagethread')

          @else

            {!! clean_ccp_html($message->body) !!}

          @endif

        </div>
      </div>
    </div>

    <div>
      <i class="fas fa-clock bg-gray"></i>
    </div>
  </div>

@stop

@push('javascript')

  @include('web::includes.javascript.id-to-name')

@endpush
