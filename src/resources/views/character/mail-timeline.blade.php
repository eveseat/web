@extends('web::layouts.grids.12')

@section('title', trans('web::seat.mail_timeline'))
@section('page_header', trans('web::seat.mail_timeline'))

@section('full')

  {!! $messages->render() !!}

  <ul class="timeline">

    @foreach(
      $messages->groupBy(function($message) {
        return carbon($message->timestamp)->format('l jS \\of F Y');
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
            <span class="time" style="text-align: right">
              <i class="fa fa-clock-o"></i>
              {{ $message->timestamp }} ({{ human_diff($message->timestamp) }})
              </br>
              <b>{{ trans_choice('web::seat.character', 1) }} {{ trans('web::seat.source') }}: </b>
              {!! $message->source_view() !!}
            </span>
            <h2 class="timeline-header">
              <b>{{ trans('web::seat.from') }}: </b>
              {!! $message->from_view() !!}

        @if ($message->recipients->where('recipient_type', 'alliance')->count() > 0)

          <li>
            <b>{{ trans('web::seat.to_alliance') }}:</b>

            @foreach($message->recipients->where('recipient_type', 'alliance') as $recipient)

              @include('web::partials.alliance', ['alliance' => $recipient->recipient_id, 'character_id' => $message->character_id])

            @endforeach

          </li>
        @endif

        @if ($message->recipients->where('recipient_type', 'corporation')->count() > 0)

          <li>
            <b>{{ trans('web::seat.to_corp') }}:</b>

            @foreach($message->recipients->where('recipient_type', 'corporation') as $recipient)

              {!! $recipient->corporation_view() !!}

            @endforeach

          </li>
        @endif

        @if($message->recipients->where('recipient_type', 'character')->count() > 0)

          <li>
            <b>{{ trans('web::seat.to_char') }}:</b>

            @foreach($message->recipients->where('recipient_type', 'character') as $recipient)

              {!! $recipient->character_view() !!}

            @endforeach

          </li>

        @endif

        @if($message->recipients->where('recipient_type', 'mailing_list')->count() > 0)

          <li>
            <b>{{ trans('web::seat.to_mailing_list') }}:</b>

            @foreach($message->recipients->where('recipient_type', 'mailing_list') as $recipient)

              @if (! is_null($recipient->mailing_list))
                {{ $recipient->mailing_list->name }}
              @else
                {{ trans('web::seat.unknown') }}
              @endif

            @endforeach

          </li>

        @endif


          </h2>
          <div class="timeline-body">

            @if($message->body)

              @if(setting('mail_threads') == "yes")


                @include('web::character.partials.messagethread')

              @else

                {!! clean_ccp_html($message->body) !!}

              @endif

            @else

              {{ trans('web::seat.missing_body') }}

            @endif

          </div>
          <div class="timeline-footer">
            <a href="{{ route('character.view.mail.timeline.read', ['message_id' => $message->mail_id]) }}"
               class="btn btn-primary btn-xs">
              Read more
            </a>
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

@push('javascript')

  @include('web::includes.javascript.id-to-name')

@endpush
