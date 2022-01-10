<div class="modal fade" id="topMailContentModal" tabindex="-1" role="dialog"
     aria-labelledby="topMailContentModalLabel">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="topMailContentModalLabel">{{ trans('web::seat.mail') }}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
        <div class="modal-footer">
          <ul class="list-inline w-100 d-flex justify-content-around">
            <li class="list-inline-item">
              <span class="badge badge-warning">0</span> Corporation
            </li>
            <li class="list-inline-item">
              <span class="badge badge-primary">0</span> Alliance
            </li>
            <li class="list-inline-item">
              <span class="badge badge-info">0</span> Characters
            </li>
            <li class="list-inline-item">
              <span class="badge badge-success">0</span> Mailing-Lists
            </li>
          </ul>
        </div>
      </div>
    </div>

  </div>
</div>

@include('web::common.mails.modals.read.read')
