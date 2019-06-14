<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title">{{ trans('web::seat.general') }}</h4>
  </div>
  <div class="panel-body">
    <form id="role-form" enctype="multipart/form-data" method="post" action="{{ route('configuration.access.roles.update', ['id' => $role->id]) }}">
      {{ csrf_field() }}
      {{ method_field('PUT') }}
      <div class="form-group">
        <label for="role-title">{{ trans_choice('web::seat.name', 1) }}</label>
        <input type="text" class="form-control" name="title" id="role-title" value="{{ $role->title }}" />
      </div>
      <div class="form-group">
        <label for="role-description">{{ trans('web::seat.description') }}</label>
        <textarea class="form-control" rows="3" name="description" id="role-description">{{ $role->description }}</textarea>
      </div>
      <div class="form-group">
        <label for="role-logo">Logo</label>
        <div class="media">
          <div class="media-left media-middle">
            <img src="{{ $role->logo }}" width="128" height="128" class="media-object" />
          </div>
          <div class="media-body">
            <input type="file" name="logo" id="role-logo" />
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="panel-footer clearfix">
    <div class="btn-group pull-right" role="group">
      <form method="post" action="{{ route('configuration.access.roles.delete', ['id' => $role->id]) }}" id="role-delete-form">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
      </form>
      <button type="submit" form="role-delete-form" class="btn btn-danger">{{ trans('web::seat.delete') }}</button>
      <input type="submit" form="role-form" class="btn btn-success" />
    </div>
  </div>
</div>