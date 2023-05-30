<div class="card">
  <div class="card-header">
    <h4 class="card-title">{{ trans('web::seat.general') }}</h4>
  </div>
  <div class="card-body">
    <form id="role-form" enctype="multipart/form-data" method="post" action="{{ route('seatcore::configuration.access.roles.update', [$role->id]) }}">
      {{ csrf_field() }}
      {{ method_field('PUT') }}
      <div class="form-group row">
        <label for="role-title" class="col-form-label col-md-4">{{ trans_choice('web::seat.name', 1) }}</label>
        <div class="col-md-8">
          <input type="text" class="form-control" name="title" id="role-title" value="{{ $role->title }}" />
        </div>
      </div>
      <div class="form-group row">
        <label for="role-description" class="col-form-label col-md-4">{{ trans('web::seat.description') }}</label>
        <div class="col-md-8">
          <textarea class="form-control" rows="3" name="description" id="role-description">{{ $role->description }}</textarea>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-md-4">
          <div class="media">
            <div class="media-left media-middle">
              <img src="{{ $role->logo }}" width="128" height="128" class="border media-object" id="role-logo" />
              <input type="file" name="logo" class="d-none" />
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="card-footer">
    <form method="post" action="{{ route('seatcore::configuration.access.roles.delete', [$role->id]) }}" id="role-delete-form">
      {{ csrf_field() }}
      {{ method_field('DELETE') }}
    </form>
    <div class="btn-group float-right" role="group">
      <button type="submit" form="role-delete-form" class="btn btn-danger">{{ trans('web::seat.delete') }}</button>
      <button type="submit" form="role-form" class="btn btn-success">Submit</button>
    </div>
  </div>
</div>

@push('javascript')
  <script>
      $('#role-logo')
          .on('mouseenter', function () {
              $(this).addClass('border-info');
          })
          .on('mouseleave', function () {
              $(this).removeClass('border-info');
          })
          .on('click', function () {
              $('input[name="logo"]').click();
          });

      $('input[name=logo]').on('change', function () {
          readImage(this, '#role-logo');
      });

      function readImage(input, id) {
          if (input.files && input.files[0]) {
              var reader = new FileReader();

              reader.onload = function (e) {
                  $(id).attr('src', e.target.result);
              };

              reader.readAsDataURL(input.files[0]);
          }
      }
  </script>
@endpush