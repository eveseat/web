<div class="modal fade" id="transactionContentModal" tabindex="-1" role="dialog"
     aria-labelledby="transactionContentModalLabel">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="transactionContentModalLabel">{{ trans('web::seat.wallet_transactions') }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <table class="table table-condensed table-hover table-striped" id="character-transactions">
          <thead>
            <tr>
              <th>{{ trans('web::wallet.date') }}</th>
              <th>{{ trans('web::wallet.order') }}</th>
              <th>{{ trans('web::wallet.type') }}</th>
              <th>{{ trans('web::wallet.location') }}</th>
              <th>{{ trans('web::wallet.price') }}</th>
              <th>{{ trans('web::wallet.quantity') }}</th>
              <th>{{ trans('web::wallet.total') }}</th>
              <th>{{ trans('web::wallet.party') }}</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>

  </div>
</div>