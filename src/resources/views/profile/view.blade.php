@extends('web::layouts.grids.6-6')

@section('title', 'User Profile')
@section('page_header', 'User Profile')

@inject('profile', '\Seat\Services\Settings\Profile')

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">User Preferces</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('profile.update.user.settings') }}" method="post">
        {{ csrf_field() }}

        <div class="box-body">

          <!-- Select Basic -->
          <div class="form-group">
            <label class="col-md-4 control-label" for="skin">SeAT Skin</label>
            <div class="col-md-4">
              <select id="skin" name="skin" class="form-control">
                @foreach($profile->options['skins'] as $skin)
                  <option value="{{ $skin }}"
                          @if(setting('skin') == $skin)
                            selected
                          @endif>
                    {{ $skin }}</option>
                @endforeach
              </select>
            </div>
          </div>

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-primary">
            Update
          </button>
        </div>
      </form>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        User Account
        <span class="pull-right">
          Last Login: {{ auth()->user()->last_login }}
          ({{ human_diff(auth()->user()->last_login) }})
        </span>
      </h3>
    </div>
    <div class="panel-body">

      <div class="row">
        <div class="col-md-6">

          <ul class="list-unstyled">
            <li class="list-header">Account Settings</li>
            <li>
              <a href="#">
                <i class="fa fa-lock"></i>
                Change Password
              </a>
            </li>
            <li>
              <a href="#">
                <i class="fa fa-list"></i>
                View Login History
              </a>
            </li>
          </ul>

        </div>
        <div class="col-md-6">

          <ul class="list-unstyled">
            <li class="list-header">Roles</li>
            @foreach($user->roles as $role)
              <li>
                <i class="fa fa-group"></i>
                <span @if($role->title == 'Superuser') class="text-danger" @endif>
                  {{ $role->title }}
                </span>
              </li>
            @endforeach
          </ul>

        </div>
      </div>

    </div>
    <div class="panel-footer">
      @if(auth()->user()->hasSuperUser())
        <span class="label label-danger">
          Superuser
        </span>
      @endif

      <span class="pull-right">
        {{ count($user->keys) }} owned keys.
      </span>
    </div>
  </div>

  <span class="text-center">
    For any account related enquiries, including permissions amendments, please contact the SeAT administrator.
  </span>

@stop

