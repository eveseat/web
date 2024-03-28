@extends('web::layouts.grids.4-8')

@section('title', trans('web::seat.edit_user'))
@section('page_header', trans('web::seat.edit_user'))
@section('page_description', $user->name)

@section('left')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.edit_user') }}</h3>
    </div>
    <div class="card-body">

      <form role="form" action="{{ route('seatcore::configuration.users.update', ['user_id' => $user->id]) }}" method="post">
        {{ csrf_field() }}
        {!! method_field('put') !!}
        <input type="hidden" name="user_id" value="{{ $user->id }}">

        <div class="box-body">

          <div class="form-group row">
            <label for="username" class="col-form-label col-md-4">{{ trans_choice('web::seat.username', 1) }}</label>
            <div class="col-md-8">
              <input type="text" name="username" class="form-control" id="username" value="{{ $user->name }}" disabled>
            </div>
          </div>

          <div class="form-group row">
            <label for="email" class="col-form-label col-md-4">{{ trans_choice('web::seat.email', 1) }}</label>
            <div class="col-md-8">
              <input type="email" name="email" class="form-control" id="email" value="{{ $user->email }}">
            </div>
          </div>

          <div class="form-group row">
              <label for="member_since" class="col-form-label col-md-4">{{ trans('web::seat.member_since') }}</label>
              <div class="col-md-8">
                  <input type="text" class="form-control" id="member_since" value="{{ $user->created_at }}" disabled />
              </div>
          </div>

          <div class="form-group row">
            <div class="offset-md-4 col-md-8">
              <div class="form-check">
                @if($user->isAdmin())
                  @if($user->id == auth()->user()->id)
                    <input type="checkbox" name="admin" value="1" id="admin" class="form-check-input" checked="checked" disabled="disabled" />
                  @else
                    <input type="checkbox" name="admin" value="1" id="admin" class="form-check-input" checked="checked" />
                  @endif
                @else
                  @if($user->id == auth()->user()->id)
                    <input type="checkbox" name="admin" value="1" id="admin" class="form-check-input" disabled="disabled" />
                  @else
                    <input type="checkbox" name="admin" value="1" id="admin" class="form-check-input" />
                  @endif
                @endif
                <label for="admin" class="form-check-label col-md-6">{{ trans_choice('web::settings.admin', 1) }}</label>
              </div>
              <small class="form-text text-danger">{{ trans('web::settings.admin_assist_edit') }}</small>
            </div>
          </div>

        </div><!-- /.box-body -->

        <div class="box-footer">

          @if(auth()->user()->id != $user->id)
            <a href="{{ route('seatcore::configuration.users.edit.account_status', ['user_id' => $user->id]) }}"
               class="btn btn-{{ $user->active ? 'danger' : 'success' }} float-left">
              @if($user->active)
                <i class="fas fa-user-slash"></i>
                {{ trans('web::seat.deactivate_user') }}
              @else
                <i class="fas fa-user-check"></i>
                {{ trans('web::seat.activate_user') }}
              @endif
            </a>
          @endif
          <button type="submit" class="btn btn-warning float-right">
            <i class="fas fa-pencil-alt"></i>
            {{ trans('web::seat.edit') }}
          </button>
        </div>
      </form>

    </div>
  </div>

  <!-- characters -->
  @if($user->name != 'admin')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans_choice('web::seat.character', 0) }}</h3>
    </div>
    <div class="card-body pt-0 pb-0">

      <ul class="list-group list-group-flush">

        @can('global.invalid_tokens')

          @foreach($user->all_characters() as $character)
            <li class="list-group-item">

              @if ($character->refresh_token)
                <button data-toggle="tooltip" title="Valid Token" class="btn btn-sm btn-link">
                  <i class="fa fa-check text-success"></i>
                </button>
              @else
                <button data-toggle="tooltip" title="Invalid Token" class="btn btn-sm btn-link">
                  <i class="fa fa-exclamation-triangle text-danger"></i>
                </button>
              @endif

              @if($character->refresh_token)
                @include('web::profile.buttons.scopes')
              @else
                @include('web::profile.buttons.noscopes')
              @endif

              @include('web::configuration.users.buttons.transfer')

              @include('web::partials.character', ['character' => $character])

            </li>
          @endforeach

        @else

          @foreach($user->characters as $character)
            <li class="list-group-item">

              @if ($character->refresh_token)
                <button data-toggle="tooltip" title="Valid Token" class="btn btn-sm btn-link">
                  <i class="fa fa-check text-success"></i>
                </button>
              @else
                <button data-toggle="tooltip" title="Invalid Token" class="btn btn-sm btn-link">
                  <i class="fa fa-exclamation-triangle text-danger"></i>
                </button>
              @endif

              @if($character->refresh_token)
                @include('web::profile.buttons.scopes')
              @endif

              @include('web::configuration.users.buttons.transfer')

              @include('web::partials.character', ['character' => $character])

            </li>
          @endforeach

        @endcan

      </ul>

    </div>
  </div>
  @endif

  @include('web::configuration.users.modals.transfer.transfer')

@stop
@section('right')

  <div class="row">

    <div class="col-md-12">

      <!-- role summary -->
      @if($user->name != 'admin')
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ trans('web::seat.role_summary') }}</h3>
        </div>
        <div class="card-body">

          <table class="table table-hover table-condensed" id="roles">
            <thead>
              <tr>
                <th>{{ trans_choice('web::seat.role_name', 1) }}</th>
                <th></th>
                <th>{{ trans_choice('web::seat.permission', 2) }}</th>
                <th>{{ trans_choice('web::seat.filter', 2) }}</th>
              </tr>
            </thead>
            <tbody>

              @foreach($user->roles as $role)
                @foreach($role->permissions as $permission)
                  <tr>
                    <td>{{ $role->title }}</td>
                    <td>
                      @if(auth()->user()->id != $user->id)
                        <div class="btn-group btn-group-sm float-right">
                          <a href="{{ route('seatcore::configuration.access.roles.edit', [$role->id]) }}" type="button"
                             class="btn btn-warning">
                            <i class="fas fa-pencil-alt"></i>
                            {{ trans('web::seat.edit') }}
                          </a>
                          <form method="post" action="{{ route('seatcore::configuration.access.roles.edit.remove.user', ['role_id' => $role->id, 'user_id' => $user->id]) }}">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button type="submit" class="btn btn-danger">
                              <i class="fas fa-trash-alt"></i>
                              {{ trans('web::seat.remove') }}
                            </button>
                          </form>
                        </div>
                      @endif
                    </td>
                    <td>
                      <span class="badge badge-{{ $user->isAdmin() ? 'danger' : 'info' }}">
                        {{ Str::studly($permission->title) }}
                      </span>
                    </td>
                    <td>
                      @if($permission->pivot->filters)
                        @foreach(json_decode($permission->pivot->filters) as $type => $entities)
                          @foreach($entities as $entity)
                            @switch($type)
                              @case('character')
                                {!! img('characters', 'portrait', $entity->id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                              @break
                              @case('corporation')
                                {!! img('corporations', 'logo', $entity->id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                              @break
                              @case('alliance')
                                {!! img('alliances', 'logo', $entity->id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                              @break
                            @endswitch
                            {{ $entity->text }}
                          @endforeach
                        @endforeach
                      @endif
                    </td>
                  </tr>
                @endforeach
              @endforeach

            </tbody>
          </table>

        </div>
        <div class="card-footer">
          <i class="text-muted float-right">{{ $user->roles->count() }} {{ trans_choice('web::seat.role', $user->roles->count()) }}</i>
        </div>
      </div>
      @endif

    </div>

    <div class="col-md-12">

      <!-- login history -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ trans('web::seat.login_history') }}</h3>
        </div>
        <div class="card-body">

          <table class="table table-hover table-condensed">
            <thead>
              <tr>
                <th>{{ trans_choice('web::seat.date', 1) }}</th>
                <th>{{ trans_choice('web::seat.source', 1) }}</th>
                <th>{{ trans_choice('web::seat.user_agent', 1) }}</th>
                <th>{{ trans_choice('web::seat.action', 1) }}</th>
              </tr>
            </thead>
            <tbody>

              @foreach($login_history as $history)

                <tr>
                  <td>
                    <span data-toggle="tooltip" title="" data-original-title="{{ $history->created_at }}">
                      {{ human_diff($history->created_at) }}
                    </span>
                  </td>
                  <td>{{ $history->source }}</td>
                  <td>
                    <span data-toggle="tooltip" title="" data-original-title="{{ $history->user_agent }}">
                      {{ Str::limit($history->user_agent, 60, '...') }}
                    </span>
                  </td>
                  <td>{{ ucfirst($history->action) }}</td>
                </tr>

              @endforeach

            </tbody>
          </table>

        </div>
      </div>

    </div><!-- ./col-md-12 -->

  </div><!-- ./row -->

  @include('web::profile.modals.scopes.scopes')

@stop

@push('javascript')

  @include('web::includes.javascript.id-to-name')

  <script>

    $('#roles').DataTable({
        'columns': [
            {'visible': false},
            {'visible': false},
            {},
            {}
        ],
    'drawCallback': function (settings) {
        var last = null;
        var api = this.api();
        var rows = api.rows({
            page:'current'
        }).nodes();

        $(api.cells({page:'current'})[0])
            .each(function (i, cell) {
                if (cell.column === 0) {
                    group = api.cell(this).data();

                    if (last !== group) {
                        $(rows).eq((i === 0) ? 0 : (i / 4))
                            .before('<tr class="bg-gray"><th class="align-middle">' + group + '</th><th>' + api.cell(cell.row, 1).data() + '</th></tr>');

                        last = group;
                    }
                }
            });

        ids_to_names(); }
    });

    $('#scopesModal').on('show.bs.modal', function (e) {
        var body = $(e.target).find('.modal-body');
        body.html('Loading...');

        $.ajax($(e.relatedTarget).data('url'))
            .done(function (data) {
                body.html(data);
            });
    });
  </script>

@endpush
