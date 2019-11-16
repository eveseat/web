<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="historyModalLabel">{{ trans('web::seat.login_history') }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <table class="table table-condensed table-hover table-striped">
          <thead>
            <tr>
              <th>{{ trans('web::seat.date') }}</th>
              <th>{{ trans('web::seat.action') }}</th>
              <th>{{ trans('web::seat.source') }}</th>
              <th>{{ trans('web::seat.user_agent') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($history as $entry)
              <tr>
                <td>
                  <span data-toggle="tooltip" title=""
                        data-original-title="{{ $entry->created_at }}">{{ human_diff($entry->created_at) }}</span>
                </td>
                <td>{{ ucfirst($entry->action) }}</td>
                <td>{{ $entry->source }}</td>
                <td>{{ Str::limit($entry->user_agent) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>

      </div>
    </div>
  </div>
</div>
