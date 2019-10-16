<div class="accordion" id="accordion">

  @foreach(evemail_threads($message->body->body) as $thread_id => $thread_message)

    <div class="card">

      <div class="card-header bg-light" id="heading-{{ $message->mail_id }}-{{ $thread_id }}">
        <h2 class="mb-0">
          <button class="btn btn-link @if(! $loop->first) collapsed @endif" type="button"
                  data-toggle="collapse" data-target="#collapse-{{ $message->mail_id }}-{{ $thread_id }}"
                  aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse-{{ $message->mail_id }}-{{ $thread_id }}">

            @if($thread_message['headers_ok'] == true)
              "{{ $thread_message['subject'] }}"
              from {{ $thread_message['from'] }}
              to {{ $thread_message['to'] }}
              sent {{ $thread_message['sent']->diffForHumans() }}
            @else
              "{{ $message->subject }}"
              sent {{ human_diff($message->timestamp) }}
            @endif

            <span class="float-right">#{{ $thread_id + 1 }}</span>
          </button>
        </h2>
      </div>

      <div id="collapse-{{ $message->mail_id }}-{{ $thread_id }}" class="collapse @if($loop->first) show @endif"
           data-parent="#accordion" aria-labelledby="heading-{{ $message->mail_id }}-{{ $thread_id }}">
        <div class="card-body">

          {!! clean_ccp_html($thread_message['message']) !!}

        </div>
      </div>
    </div>

  @endforeach

</div>
