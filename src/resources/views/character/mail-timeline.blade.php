@extends('web::layouts.grids.12')

@section('title', trans('web::seat.mail_timeline'))
@section('page_header', trans('web::seat.mail_timeline'))

@section('full')

  {!! $messages->render() !!}

  <div class="timeline">

    @foreach(
      $messages->groupBy(function($message) {
        return carbon($message->timestamp)->format('l jS \\of F Y');
      }) as $day => $digest
    )
      <div class="time-label">
        <span class="bg-blue">
          {{ $day }}
        </span>
      </div>

        @foreach($digest as $message)

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
                    @switch($message->sender->category)
                      @case('character')
                        @include('web::partials.character', ['character' => $message->sender])
                        @break
                      @case('corporation')
                        @include('web::partials.corporation', ['corporation' => $message->sender])
                        @break
                      @case('alliance')
                        @include('web::partials.alliance', ['alliance' => $message->sender])
                        @break
                    @endswitch
                  </li>

                  @if($message->recipients->where('recipient_type', 'alliance')->isNotEmpty())
                    <li>
                      <b>{{ trans('web::seat.to_alliance') }}:</b>

                      @foreach($message->recipients->where('recipient_type', 'alliance') as $recipient)

                        @include('web::partials.alliance', ['alliance' => $recipient->entity])

                      @endforeach

                    </li>
                  @endif

                  @if($message->recipients->where('recipient_type', 'corporation')->isNotEmpty())
                    <li>
                      <b>{{ trans('web::seat.to_corp') }}:</b>

                      @foreach($message->recipients->where('recipient_type', 'corporation') as $recipient)

                        @include('web::partials.corporation', ['corporation' => $recipient->entity])

                      @endforeach

                    </li>
                  @endif

                  @if($message->recipients->where('recipient_type', 'character')->isNotEmpty())
                    <li>
                      <b>{{ trans('web::seat.to_char') }}:</b>

                      @foreach($message->recipients->where('recipient_type', 'character') as $recipient)

                        @include('web::partials.character', ['character' => $recipient->entity])

                      @endforeach

                    </li>
                  @endif
                </ul>
              </h3>
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
                <a href="{{ route('seatcore::character.view.mail.timeline.read', ['message_id' => $message->mail_id]) }}"
                   class="btn btn-primary btn-sm">
                  Read more
                </a>
              </div>
            </div>
          </div>

        @endforeach {{-- ./messages --}}

      @endforeach {{-- ./days --}}

    <div>
      <i class="fas fa-clock bg-gray"></i>
    </div>
  </div>

  {!! $messages->render() !!}

@stop

@push('javascript')

  @include('web::includes.javascript.id-to-name')

@endpush
