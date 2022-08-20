<h4 class="page-header">{{ $mail->subject }}</h4>

<ul class="list-unstyled">
  <li>
    <b>{{ trans('web::mail.sent') }}: </b>
    @include('web::partials.date', ['datetime' => $mail->timestamp])
  </li>
  <li>
    <b>{{ trans('web::mail.author') }}: </b>
    @switch($mail->sender->category)
      @case('character')
        @include('web::partials.character', ['character' => $mail->sender])
        @break
      @case('corporation')
        @include('web::partials.corporation', ['corporation' => $mail->sender])
        @break
      @case('alliance')
        @include('web::partials.alliance', ['alliance' => $mail->sender])
        @break
    @endswitch
  </li>
  @if($mail->recipients->where('recipient_type', 'character')->isNotEmpty())
  <li>
    <b>{{ trans_choice('web::seat.character', 0) }}: </b>
    @foreach($mail->recipients->where('recipient_type', 'character') as $character)
      @include('web::partials.character', ['character' => $character->entity])
    @endforeach
  </li>
  @endif
    @if($mail->recipients->where('recipient_type', 'mailing_list')->isNotEmpty())
      <li>
        <b>{{ trans('web::mail.mailing_list') }}: </b>
        {{ $mail->recipients->where('recipient_type', 'mailing_list')->implode('mailing_list.name', ', ') }}
      </li>
    @endif
    @if($mail->recipients->where('recipient_type', 'corporation')->isNotEmpty())
      <li>
        <b>{{ trans_choice('web::seat.corporation', 0) }}: </b>
        @foreach($mail->recipients->where('recipient_type', 'corporation') as $corporation)
          @include('web::partials.corporation', ['corporation' => $corporation->entity])
        @endforeach
      </li>
    @endif
    @if($mail->recipients->where('recipient_type', 'alliance')->isNotEmpty())
      <li>
        <b>{{ trans_choice('web::seat.alliance', 1) }}: </b>
        @foreach($mail->recipients->where('recipient_type', 'alliance') as $alliance)
          @include('web::partials.alliance', ['alliance' => $alliance->entity])
        @endforeach
      </li>
    @endif
</ul>

<hr/>

<p>{!! strip_tags($mail->body->body, '<br>') !!}</p>