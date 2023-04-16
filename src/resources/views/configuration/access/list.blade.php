@extends('web::layouts.grids.4-8')

@section('title', trans('web::seat.access_mangement'))
@section('page_header', trans('web::seat.access_mangement'))

@section('left')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.quick_add_role') }}</h3>
    </div>
    <div class="card-body">

      <form role="form" method="post" action="{{ route('seatcore::configuration.access.roles.store') }}">
        {{ csrf_field() }}

        <div class="box-body">

          <div class="form-group row">
            <label for="role-title" class="col-form-label col-md-4">{{ trans('web::seat.role_name') }}</label>
            <div class="col-md-8">
              <input type="text" name="title" class="form-control" id="role-title" placeholder="Enter role name">
            </div>
          </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-success float-right">
            <i class="fas fa-plus-square"></i>
            {{ trans('web::seat.add_new_role') }}
          </button>
        </div>
      </form>

    </div>
  </div>

@stop

@section('right')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.available_roles') }}</h3>
    </div>
    <div class="card-body">

      {!! $dataTable->table(['class' => 'table table-hover table-striped table-condensed']) !!}

    </div>
    <div class="card-footer">
      <i class="float-right text-muted">{{ $nb_roles }} {{ trans_choice('web::seat.role', $nb_roles) }}</i>
    </div>
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush
