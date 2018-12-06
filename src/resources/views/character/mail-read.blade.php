<h4>
  <i class="fa fa-envelope-o"></i>
  {{ $message->subject }}
</h4>


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

<p>

  @if(setting('mail_threads') == "yes")

    @include('web::character.partials.messagethread')

  @else

    {!! clean_ccp_html($message->body->body) !!}

  @endif

</p>

