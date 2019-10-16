<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

  @foreach(evemail_threads($message->body->body) as $thread_id => $thread_message)

    <div class="panel panel-default">

      @if($thread_message['headers_ok'] == true)

        <div class="panel-heading" role="tab" id="heading-{{ $message->mail_id }}-{{ $thread_id }}">
          <h4 class="panel-title">
            <a role="button" data-widget="collapse" data-parent="#accordion"
               href="#collapse-{{ $message->mail_id }}-{{ $thread_id }}"
               aria-expanded="true" aria-controls="collapse-{{ $message->mail_id }}-{{ $thread_id }}">

              "{{ $thread_message['subject'] }}"
              from {{ $thread_message['from'] }}
              to {{ $thread_message['to'] }}
              sent {{ $thread_message['sent']->diffForHumans() }}

              <span class="float-right">#{{ $thread_id }}</span>
            </a>
          </h4>
        </div>

      @else

        <div class="panel-heading" role="tab" id="heading-{{ $message->mail_id }}-0">
          <h4 class="panel-title">
            <a role="button" data-widget="collapse" data-parent="#accordion"
               href="#collapse-{{ $message->mail_id }}-0"
               aria-expanded="true" aria-controls="collapse-{{ $message->mail_id }}-0">

              "{{ $message->subject }}"
              sent {{ human_diff($message->timestamp) }}

              <span class="float-right">#0</span>
            </a>
          </h4>
        </div>

      @endif

      <div id="collapse-{{ $message->mail_id }}-{{ $thread_id }}" role="tabpanel"
           {{-- if the headers are ok, we can work on the panel and collapse --}}
           @if($thread_message['headers_ok'] == true)

           class="panel-collapse collapse"

           @else

           class="panel-collapse collapse in"

           @endif

           aria-labelledby="heading-{{ $message->mail_id }}-{{ $thread_id }}">
        <div class="panel-body">

          {!! clean_ccp_html($thread_message['message']) !!}

        </div>
      </div>
    </div>

  @endforeach

</div>
