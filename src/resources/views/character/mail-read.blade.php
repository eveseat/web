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
    @include('web::partials.character', ['character' => $from, 'character_id' => $message->character_id])
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
      @foreach($corporations as $corporation)
        @include('web::partials.corporation', ['corporation' => $corporation, 'character_id' => $message->character_id])
      @endforeach
    </li>
  @endif

  @if($message->recipients->where('recipient_type', 'character')->count() > 0)
    <li>
      <b>To Characters:</b>
      @foreach($characters as $character)
        @include('web::partials.character', ['character' => $character, 'character_id' => $message->character_id])
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

