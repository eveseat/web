<div class="modal fade" id="journalContentModal" tabindex="-1" role="dialog"
     aria-labelledby="journalContentModalLabel">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="journalContentModalLabel">{{ trans('web::seat.wallet_journal') }}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-condensed table-hover table-striped" id="character-journal" data-page-length=100>
          <thead>
            <tr>
              <th>{{ trans('web::seat.date') }}</th>
              <th>{{ trans_choice('web::seat.type', 1) }}</th>
              <th>{{ trans('web::seat.owner_1') }}</th>
              <th>{{ trans('web::seat.owner_2') }}</th>
              <th>{{ trans('web::seat.amount') }}</th>
              <th>{{ trans('web::seat.balance') }}</th>
            </tr>
          </thead>
        </table>

      </div>
    </div>
  </div>
</div>