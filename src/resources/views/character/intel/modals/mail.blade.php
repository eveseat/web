<div class="modal fade" id="topMailContentModal" tabindex="-1" role="dialog"
     aria-labelledby="topMailContentModalLabel">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="topMailContentModalLabel">{{ trans('web::seat.mail') }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <table class="table table-condensed table-hover table-striped" id="character-mail" data-page-length=50>
          <thead>
            <tr>
              <th>{{ trans('web::seat.date') }}</th>
              <th>{{ trans('web::seat.from') }}</th>
              <th>{{ trans_choice('web::seat.title', 1) }}</th>
              <th data-orderable="false">{{ trans('web::seat.to') }}</th>
              <th data-orderable="false"></th>
            </tr>
          </thead>
        </table>
        <div class="panel-footer clearfix">
          <div class="col-md-2 offset-md-2">
            <span class="badge badge-warning">0</span> Corporation
          </div>
          <div class="col-md-2">
            <span class="badge badge-primary">0</span> Alliance
          </div>
          <div class="col-md-2">
            <span class="badge badge-info">0</span> Characters
          </div>
          <div class="col-md-2">
            <span class="badge badge-success">0</span> Mailing-Lists
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

@include('web::common.mails.modals.read.read')
