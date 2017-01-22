<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

  @foreach(evemail_threads($message->body) as $thread_id => $thread_message)

    <div class="panel panel-default">

      @if($thread_message['headers_ok'] == true)

        <div class="panel-heading" role="tab" id="heading{{ $message->messageID }}{{ $thread_id }}">
          <h4 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#accordion"
               href="#collapse{{ $message->messageID }}{{ $thread_id }}"
               aria-expanded="true" aria-controls="collapse{{ $message->messageID }}{{ $thread_id }}">

              "{{ $thread_message['subject'] }}"
              from {{ $thread_message['from'] }}
              to {{ $thread_message['to'] }}
              sent {{ $thread_message['sent']->diffForHumans() }}

              <span class="pull-right">#{{ $thread_id }}</span>
            </a>
          </h4>
        </div>

      @else

        <div class="panel-heading" role="tab" id="heading{{ $message->messageID }}0">
          <h4 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#accordion"
               href="#collapse{{ $message->messageID }}0"
               aria-expanded="true" aria-controls="collapse{{ $message->messageID }}0">

              "{{ $message->title }}"
              sent {{ human_diff($message->sentDate) }}

              <span class="pull-right">#0</span>
            </a>
          </h4>
        </div>

      @endif

      <div id="collapse{{ $message->messageID }}{{ $thread_id }}"
           {{-- if the headers are ok, we can work on the panel and collapse --}}
           @if($thread_message['headers_ok'] == true)

           class="panel-collapse collapse in" role="tabpanel"

           @else

           class="panel-collapse" role="tabpanel"

           @endif

           aria-labelledby="heading{{ $thread_id }}">
        <div class="panel-body">

          {!! clean_ccp_html($thread_message['message']) !!}

        </div>
      </div>
    </div>

  @endforeach

</div>