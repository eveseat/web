<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="emailModalLabel">{{ trans('web::seat.change_email') }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form role="form" action="{{ route('seatcore::profile.update.email') }}" method="post">
          {{ csrf_field() }}

          <div class="box-body">

            <div class="form-group row">
              <label for="current_email" class="col-form-label col-md-4">{{ trans('web::seat.current_email') }}</label>
              <div class="col-md-8">
                <input type="email" name="current_email" class="form-control" placeholder="Current Email"
                       value="{{ setting('email_address') }}" disabled="disabled"/>
              </div>
            </div>

            <div class="form-group row">
              <label for="new_email" class="col-form-label col-md-4">{{ trans('web::seat.new_email') }}</label>
              <div class="col-md-8">
                <input type="email" name="new_email" class="form-control" placeholder="New Email"
                       required="required"/>
              </div>
            </div>

            <div class="form-group row">
              <label for="new_email_confirmation" class="col-form-label col-md-4">{{ trans('web::seat.confirm_new_email') }}</label>
              <div class="col-md-8">
                <input type="email" name="new_email_confirmation" class="form-control"
                       id="email_confirmation" placeholder="New Email Confirmation" required="required"/>
              </div>
            </div>

          </div><!-- /.box-body -->

          <div class="box-footer">
            <button type="submit" class="btn btn-primary float-right">
              {{ trans('web::seat.change_email') }}
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
