@if(count($mail) > 0)

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Mail Results</h3>
    </div>
    <div class="panel-body">

      <table class="table datatable compact table-condensed table-hover table-responsive">
        <thead>
        <tr>
          <th>{{ trans_choice('web::seat.name', 1) }}</th>
          <th>{{ trans_choice('web::seat.ceo', 1) }}</th>
        </tr>
        </thead>
        <tbody>

        @foreach($mail as $message)

          <tr>
            <td data-order="{{ $message->sentDate }}">
              <span data-toggle="tooltip" title="" data-original-title="{{ $message->sentDate }}">
                {{ human_diff($message->sentDate) }}
              </span>
            </td>
            <td>
              {!! img('auto', $message->senderID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $message->senderName }}
            </td>
            <td>
              <i class="fa fa-comment" data-toggle="popover" data-placement="top" title=""
                 data-trigger="hover" data-content="{{ str_limit(strip_tags($message->body), 200, '...') }}"></i>
              {{ str_limit($message->title, 50, '...') }}
            </td>
            <td>
              @if($message->toCorpOrAllianceID != 0)
                <span data-toggle="tooltip" data-placement="top" title=""
                      data-original-title="Corporations / Alliances">
                  <span class="label label-primary">{{ count(explode(',', $message->toCorpOrAllianceID)) }}</span>
                </span>
              @endif

              @if($message->toCharacterIDs)
                <span data-toggle="tooltip" data-placement="top" title=""
                      data-original-title="Characters">
                  <span class="label label-info">{{ count(explode(',', $message->toCharacterIDs)) }}</span>
                </span>
              @endif

              @if($message->toListID)
                <span data-toggle="tooltip" data-placement="top" title=""
                      data-original-title="Mailing Lists">
                  <span class="label label-success">{{ count(explode(',', $message->toListID)) }}</span>
                </span>
              @endif
            </td>
            <td>
              <a href="{{ route('character.view.mail.timeline.read', ['message_id' => $message->messageID]) }}"
                 class="btn btn-primary btn-xs">
                <i class="fa fa-envelope"></i> Read
              </a>
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>


    </div>
  </div>

@endif