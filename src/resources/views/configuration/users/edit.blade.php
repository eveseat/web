@extends('web::layouts.grids.4-8')

@section('title', trans('web::seat.edit_user'))
@section('page_header', trans('web::seat.edit_user'))
@section('page_description', $user->name)

@inject('request', 'Illuminate\Http\Request')

@section('left')

  <!-- account re-assignment -->
  @if($user->name != 'admin')
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.reassign_user') }}</h3>
    </div>
    <div class="panel-body">

      <div>
        <dl>
          <dt>Current User Group</dt>
          <dd>
            <ul class="list-unstyled">
              @foreach($user->group->users as $group_user)
                <li>
                  {!! img('character', $group_user->id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  {{ $group_user->name }}
                </li>
              @endforeach
            </ul>
          </dd>
        </dl>
      </div>

      <form role="form" action="{{ route('configuration.access.users.reassign') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="user_id" value="{{ $user->id }}">

        <div class="form-group">
          <label for="available_users">{{ trans_choice('web::seat.available_groups', 2) }}</label>
          <select name="group_id" id="available_users" style="width: 100%">

            @foreach($groups as $group)

              <option value="{{ $group->id }}">
                {{ $group->users->map(function($user) { return $user->name; })->implode(', ') }}
              </option>

            @endforeach

          </select>
        </div>

        <div class="box-footer">

          @if(auth()->user()->id != $user->id && $user->active)
            <a type="button" class="btn btn-warning pull-left" data-toggle="modal" data-target="#deactivateModal">

              {{ trans('web::seat.deactivate_user') }}
            </a>

            <!-- Deactivate Modal -->
            <div class="modal fade" id="deactivateModal" tabindex="-1" role="dialog" aria-labelledby="deactivateModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="deactivateModalLabel">
                      Add a Note
                    </h4>
                  </div>
                  <div class="modal-body">

                    <div class="callout callout-info">
                      <h4>Deactivate users with caution!</h4>

                      <p>Deactivating a user means moving the user to another user group. This method is generally used, when a user doesn't have access to the character anymore and is not able to recover a new refresh_token. This means explicitly: sold or bio massed characters.</p>
                    </div>

                    <form id="deactivateUserForm" role="form" action="{{ route('configuration.users.edit.account_status', ['user_id' => $user->id]) }}" method="post">
                      {{ csrf_field() }}

                      <div class="box-body">

                        <div class="form-group">
                          <label for="text">Title</label>
                          <select class="form-control" name="title" id="title">
                            <option>Character has been sold</option>
                            <option>Character has been bio massed</option>
                          </select>
                        </div>

                        <div class="form-group">
                          <label>Note</label>
                          <textarea class="form-control" rows="15" name="note" placeholder="Explain the nature of the request, include buyer character_id if character has been sold ..." required></textarea>
                        </div>

                      </div><!-- /.box-body -->

                      <div class="box-footer">
                        <button type="submit" form="deactivateUserForm" class="btn btn-primary pull-right">
                          Add
                        </button>
                      </div>
                    </form>

                  </div>
                </div>
              </div>
            </div>

          @endif

          <button type="submit" class="btn btn-primary pull-right">
            {{ trans('web::seat.reassign') }}
          </button>

        </div>
      </form>

    </div>
  </div>
  @endif

@stop
@section('right')

  <div class="row">

    <div class="col-md-12">

      <!-- role summary -->
      @if($user->name != 'admin')
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::seat.role_summary') }}</h3>
        </div>
        <div class="panel-body">

          <table class="table table-hover table-condensed">
            <tbody>
            <tr>
              <th>{{ trans_choice('web::seat.role_name', 1) }}</th>
              <th>{{ trans_choice('web::seat.permission', 2) }}</th>
              <th>{{ trans_choice('web::seat.affiliation', 2) }}</th>
              <th></th>
            </tr>

            @foreach($user->group->roles as $role)

              <tr>
                <td>{{ $role->title }}</td>
                <td>
                  @foreach($role->permissions as $permission)
                    <span
                        class="label label-{{ $permission->title == 'superuser' ? 'danger' : 'info' }}">{{ studly_case($permission->title) }}</span>
                  @endforeach
                </td>
                <td>
                  @foreach($role->affiliations as $affiliation)
                    <span class="label label-primary">{{ $affiliation->affiliation }} ({{ $affiliation->type }})</span>
                  @endforeach
                </td>
                <td>
                  @if(auth()->user()->id != $user->id)
                    <a href="{{ route('configuration.access.roles.edit', ['id' => $role->id]) }}" type="button"
                       class="btn btn-warning btn-xs">
                      {{ trans('web::seat.edit') }}
                    </a>
                    <a href="{{ route('configuration.access.roles.edit.remove.group', ['role_id' => $role->id, 'user_id' => $user->group->id]) }}"
                       type="button" class="btn btn-danger btn-xs">
                      {{ trans('web::seat.remove') }}
                    </a>
                  @endif
                </td>

              </tr>

            @endforeach

            </tbody>
          </table>

        </div>
        <div class="panel-footer">
          <b>{{ count($user->group->roles) }}</b> {{ trans_choice('web::seat.role', count($user->group->roles)) }}
        </div>
      </div>
      @endif

    </div>

    <div class="col-md-12">

      <!-- login history -->
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::seat.login_history') }}</h3>
        </div>
        <div class="panel-body">

          <table class="table table-hover table-condensed">
            <tbody>
            <tr>
              <th>{{ trans_choice('web::seat.date', 1) }}</th>
              <th>{{ trans_choice('web::seat.source', 1) }}</th>
              <th>{{ trans_choice('web::seat.user_agent', 1) }}</th>
              <th>{{ trans_choice('web::seat.action', 1) }}</th>
              <th></th>
            </tr>

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
                    {{ str_limit($history->user_agent, 60, '...') }}
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

    <div class="col-md-12">

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">{{ trans('web::seat.note') }}</h3>
          <!-- Button trigger modal -->
          <span class="pull-right">
          <a type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#createModal">
            {{ trans('web::seat.add') }} {{ trans('web::seat.note') }}
          </a>
        </span>
        </div>
        <div class="box-body">
          <table class="table compact table-condensed table-hover table-responsive"
                 id="character-notes">
            <thead>
            <tr>
              <th>{{ trans('web::seat.created') }}</th>
              <th>{{ trans_choice('web::seat.title',1) }}</th>
              <th>{{ trans('web::seat.note') }}</th>
              <th></th>
            </tr>
            </thead>
          </table>
        </div>
        <!-- /.box-body -->
      </div>

    </div>

  </div><!-- ./row -->

  {{-- include the note creation modal --}}
  @include('web::includes.modals.createnote',
    ['post_route' => route('character.view.intel.notes.new', ['character_id' => $request->user_id])])

  {{-- include the note edit modal --}}
  @include('web::includes.modals.editnote',
    ['post_route' => route('character.view.intel.notes.update', ['character_id' => $request->user_id])])

@stop

@push('javascript')

  @include('web::includes.javascript.id-to-name')

  <script>
    $("#available_users").select2({
      placeholder: "{{ trans('web::seat.select_group_to_assign') }}"
    });

    $(function () {
      $('table#character-notes').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax      : '{{ route('character.view.intel.notes.data', ['character_id' => $request->user_id]) }}',
        columns   : [
          {data: 'created_at', name: 'created_at', render: human_readable},
          {data: 'title', name: 'title'},
          {data: 'note', name: 'note'},
          {data: 'actions', name: 'actions', searchable: false}
        ]
      });
    })
  </script>



@endpush
