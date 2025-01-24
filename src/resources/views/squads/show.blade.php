@extends('web::layouts.grids.12')

@section('title', trans_choice('web::squads.squad', 0))
@section('page_header', trans_choice('web::squads.squad', 1))
@section('page_description', $squad->name)

@section('full')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h3>
            Summary
            @can('squads.edit', $squad)
              <div class="btn-group float-right" role="group">
                <a href="{{ route('seatcore::squads.edit', $squad) }}" class="btn btn-sm btn-warning">
                  <i class="fas fa-edit"></i>
                  Edit
                </a>
                @can('squads.create')
                  <button type="submit" class="btn btn-sm btn-danger confirmdelete" form="delete-squad">
                    <i class="fas fa-trash-alt"></i>
                    Delete
                  </button>
                @endcan
              </div>
            @endcan
          </h3>

          <hr/>

          <div class="media mb-3">
            <img src="{{ $squad->logo }}" width="128" height="128" class="align-self-center mr-3" />
            <div class="media-body">
              <p>{!! $squad->description !!}</p>
            </div>
          </div>

          <h3>Metadata</h3>

          <hr/>

          <ul class="list-group list-group-horizontal flex-fill text-center">
            <li class="list-group-item flex-fill border-0">
              <i class="fas @if($squad->is_moderated) fa-check text-success @else fa-times text-danger @endif"></i> Moderated
            </li>
            <li class="list-group-item flex-fill border-0">
              <i class="fas @if($squad->is_moderator) fa-check text-success @else fa-times text-danger @endif"></i> Moderator
            </li>
            <li class="list-group-item flex-fill border-0">
              <i class="fas @if($squad->is_member) fa-check text-success @else fa-times text-danger @endif"></i> Member
            </li>
            <li class="list-group-item flex-fill border-0">
              <i class="fas @if($squad->is_candidate) fa-check text-success @else fa-times text-danger @endif"></i> Candidate
            </li>
          </ul>

          <ul class="list-group list-group-horizontal flex-fill text-center">
            <li class="list-group-item flex-fill border-0">
              Type
              @switch($squad->type)
                @case('hidden')
                  <span class="badge badge-dark">{{ ucfirst($squad->type) }}</span>
                  @break
                @case('auto')
                  <span class="badge badge-info">{{ ucfirst($squad->type) }}</span>
                  @break
                @default
                  <span class="badge badge-success">{{ ucfirst($squad->type) }}</span>
              @endswitch
            </li>
            <li class="list-group-item flex-fill border-0">
              @can('squads.show_members', $squad)
                Members <span class="badge badge-pill badge-light">{{ $squad->members_count }}</span>
              @endcan
            </li>
            <li class="list-group-item flex-fill border-0">
              Moderators <span class="badge badge-pill badge-warning">{{ $squad->moderators_count }}</span>
            </li>
            <li class="list-group-item flex-fill border-0">
            @can('squads.manage_candidates', $squad)
              @if($squad->type == 'manual')
                Candidates <span class="badge badge-pill badge-primary">{{ $squad->applications_count }}</span>
              @endif
            @endcan
            </li>
          </ul>

          <h3>Moderators</h3>

          <hr/>

          @foreach($squad->moderators->chunk(6) as $row)
            <div class="row mt-3">
              @foreach($row as $moderator)
                <div class="col-2">
                  @include('web::partials.character', ['character' => $moderator->main_character])
                  @can('squads.manage_moderators', $squad)
                    <form method="post" action="{{ route('seatcore::squads.moderators.destroy', ['squad' => $squad, 'user' => $moderator]) }}" class="float-right">
                      {!! csrf_field() !!}
                      {!! method_field('DELETE') !!}
                      <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash-alt"></i>
                        Remove
                      </button>
                    </form>
                  @endcan
                </div>
              @endforeach
            </div>
          @endforeach

          @can('squads.manage_moderators', $squad)
            <form method="post" action="{{ route('seatcore::squads.moderators.store', $squad) }}" class="mt-3">
              {!! csrf_field() !!}
              <div class="row justify-content-end">
                <div class="col-4">
                  <div class="input-group input-group-sm">
                    <label for="squad-moderator" class="sr-only">User</label>
                    <select name="user_id" class="form-control input-sm" id="squad-moderator" style="width: 89%;"></select>
                    <span class="input-group-append">
                      <button class="btn btn-sm btn-success" type="submit">
                        <i class="fas fa-plus"></i> Add
                      </button>
                    </span>
                  </div>
                </div>
              </div>
            </form>
            <form method="post" action="{{ route('seatcore::squads.destroy', $squad) }}" id="delete-squad">
              {!! csrf_field() !!}
              {!! method_field('DELETE') !!}
            </form>
          @endcan
        </div>
        @if($squad->type != 'auto' && auth()->user()->name !== 'admin')
          <div class="card-footer">
            @if($squad->is_member)
              <form method="post" action="{{ route('seatcore::squads.members.leave', $squad) }}" id="form-leave">
                {!! csrf_field() !!}
                {!! method_field('DELETE') !!}
              </form>
              <button type="submit" class="btn btn-sm btn-danger float-right" form="form-leave">
                <i class="fas fa-sign-out-alt"></i> {{ trans('web::squads.leave') }}
              </button>
            @else
              @if($squad->is_candidate)
                <form method="post" action="{{ route('seatcore::squads.applications.cancel', $squad) }}" id="form-cancel">
                  {!! csrf_field() !!}
                  {!! method_field('DELETE') !!}
                </form>
                <div class="text-right">
                  You have applied to this squad
                  @include('web::partials.date', ['datetime' => $squad->applications->where('user_id', auth()->user()->id)->first()->created_at])

                  <button type="submit" class="btn btn-sm btn-danger" form="form-cancel">
                    <i class="fas fa-times-circle"></i> {{ trans('web::squads.cancel') }}
                  </button>
                </div>
              @endif
              @if(! $squad->is_candidate && $squad->type == 'manual' && $squad->isUserEligible(auth()->user()))
                @if($squad->moderators->isEmpty())
                  <form method="post" action="{{ route('seatcore::squads.applications.store', $squad) }}">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-sm btn-success float-right">
                      <i class="fas fa-sign-in-alt"></i> {{ trans('web::squads.join') }}
                    </button>
                  </form>
                @else
                  <button data-toggle="modal" data-target="#application-create" class="btn btn-sm btn-success float-right">
                    <i class="fas fa-sign-in-alt"></i> {{ trans('web::squads.join') }}
                  </button>
                @endif
              @endif
            @endif
          </div>
        @endif
      </div>
    </div>
  </div>

  @can('squads.manage_roles', $squad)
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Roles</h3>
          </div>
          <div class="card-body">
            <table class="table table-striped table-hover" id="roles-table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Permissions</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>
          </div>
          <div class="card-footer">
            <form method="post" action="{{ route('seatcore::squads.roles.store', $squad) }}" data-table="rolesTableBuilder" id="squad-role-form">
              {!! csrf_field() !!}
              <div class="row justify-content-end">
                <div class="col-4">
                  <div class="input-group input-group-sm">
                    <label for="squad-role" class="sr-only">Role</label>
                    <select name="role_id" class="form-control input-sm" id="squad-role" style="width: 88%;"></select>
                    <span class="input-group-append">
                      <button class="btn btn-sm btn-success" type="submit">
                        <i class="fas fa-plus"></i> Add
                      </button>
                    </span>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endcan

  @can('squads.show_members', $squad)
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Members</h3>
        </div>
        <div class="card-body">
          @can('squads.show_members', $squad)
            <table class="table table-striped table-hover" id="members-table">
              <thead>
                <tr>
                  <th>{{ trans_choice('web::squads.name', 1) }}</th>
                  <th>{{ trans_choice('web::squads.character', 0) }}</th>
                  <th>{{ trans('web::squads.member_since') }}</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>
          @endcan
          @cannot('squads.show_members', $squad)
            <p class="text-center">You are not member of that squad.</p>
          @endcannot
        </div>
        @can('squads.manage_members', $squad)
          <div class="card-footer">
            <form method="post" action="{{ route('seatcore::squads.members.store', $squad) }}" data-table="dataTableBuilder" id="squad-member-form">
              {!! csrf_field() !!}
              <div class="row justify-content-end">
                <div class="col-4">
                  <div class="input-group input-group-sm">
                    <label for="squad-member" class="sr-only">User</label>
                    <select name="user_id" class="form-control input-sm" id="squad-member" style="width: 88%;"></select>
                    <span class="input-group-append">
                      <button class="btn btn-sm btn-success" type="submit">
                        <i class="fas fa-plus"></i> Add
                      </button>
                    </span>
                  </div>
                </div>
              </div>
            </form>
          </div>
        @endcan
      </div>
    </div>
  </div>
  @endcan

  @can('squads.manage_candidates', $squad)
    @if($squad->type == 'manual' && $squad->moderators->isNotEmpty())
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Candidates</h3>
            </div>
            <div class="card-body">
              <table class="table table-striped table-hover" id="candidates-table">
                <thead>
                  <tr>
                    <th>{{ trans_choice('web::squads.name', 1) }}</th>
                    <th>{{ trans_choice('web::squads.character', 0) }}</th>
                    <th>{{ trans('web::squads.applied_at') }}</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    @endif
  @endcan

  @include('web::squads.modals.applications.create.application')
  @include('web::squads.modals.applications.read.application')

@endsection

@push('javascript')

  @include('web::includes.javascript.id-to-name')

  <script>
    window.LaravelDataTables = window.LaravelDataTables || {};

    ids_to_names();

    $('#squad-moderator')
        .select2({
            placeholder: 'Select a moderator to add to this Squad',
            ajax: {
                url: '{{ route('seatcore::squads.moderators.lookup', $squad) }}',
                dataType: 'json',
                cache: true,
                processResults: function (data, params) {
                    return {
                        results: data.results
                    };
                }
            }
        });

    $('#squad-role')
      .select2({
          placeholder: 'Select a role to add to this Squad',
          ajax: {
              url: '{{ route('seatcore::squads.roles.lookup', $squad) }}',
              dataType: 'json',
              cache: true,
              processResults: function (data, params) {
                  return {
                      results: data.results
                  };
              }
          }
      });

    $('#squad-member')
        .select2({
            placeholder: 'Select an user to add to this Squad',
            ajax: {
                url: '{{ route('seatcore::squads.members.lookup', $squad) }}',
                dataType: 'json',
                cache: true,
                processResults: function (data, params) {
                    return {
                        results: data.results
                    };
                }
            }
        });

    $('#squad-member-form, #squad-role-form')
        .on('submit', function (e) {
            e.preventDefault();

            if (! $(this).find('select').val())
                return;

            var button = $(this).find('.btn-success');

            button.attr('disabled', true);
            button.find('i').remove();
            button.prepend('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true">');

            $(this).find('.btn-success i').addClass('spinner-border');

            $.ajax({
                data: $(this).serialize(),
                url: $(this).attr('action'),
                method: $(this).attr('method'),
            }).done(function () {
                $(this).find('select').empty().trigger('change');

                button.find('.spinner-border').remove();
                button.prepend('<i class="fas fa-plus">');
                button.removeAttr('disabled');

                switch (e.currentTarget.id) {
                    case 'squad-member-form':
                        window.LaravelDataTables.membersTableBuilder.ajax.reload();
                        break;
                    case 'squad-role-form':
                        window.LaravelDataTables.rolesTableBuilder.ajax.reload();
                        break;
                }
            });

            return false;
        });

    if (! $.fn.dataTable.isDataTable('#members-table')) {
        window.LaravelDataTables["membersTableBuilder"] = $('#members-table').DataTable({
            dom: 'Bfrtip',
            processing: true,
            serverSide: true,
            order: [[0, 'asc']],
            ajax: {
                url: '{{ route('seatcore::squads.members.index', $squad) }}',
                type: 'POST',
                headers: {
                    'X-HTTP-Method-Override': 'GET'
                }
            },
            columns: [
                {data: "name", name: "name", title: "Name", "orderable": true, "searchable": true},
                {data: "characters", name: "characters", title: "Characters", "orderable": true, "searchable": true},
                {data: "member_since", name: "member_since", title: "Member Since", "orderable": false, "searchable": false},
                {defaultContent: "", data: "action", name: "action", title: "Action", "orderable": false, "searchable": false}
            ],
            "drawCallback": function() {
                $("[data-toggle=tooltip]").tooltip();
            }
        });
    }

    if (! $.fn.dataTable.isDataTable('#candidates-table')) {
        window.LaravelDataTables["candidatesTableBuilder"] = $('#candidates-table').DataTable({
            dom: 'Bfrtip',
            processing: true,
            serverSide: true,
            order: [[0, 'asc']],
            ajax: {
                url: '{{ route('seatcore::squads.applications.index', $squad) }}',
                type: 'POST',
                headers: {
                    'X-HTTP-Method-Override': 'GET'
                }
            },
            columns: [
                {data: 'user.name', name: 'user.name'},
                {data: 'characters', name: 'characters'},
                {data: 'created_at', name: 'created_at'},
                {defaultContent: "", data: "action", name: "action", title: "Action", "orderable": false, "searchable": false}
            ],
            "drawCallback": function() {
                $("[data-toggle=tooltip]").tooltip();
            }
        });
    }

    if (! $.fn.dataTable.isDataTable('#roles-table')) {
        window.LaravelDataTables["rolesTableBuilder"] = $('#roles-table').DataTable({
            dom: 'Bfrtip',
            processing: true,
            serverSide: true,
            order: [[0, 'asc']],
            ajax: {
                url: '{{ route('seatcore::squads.roles.show', $squad) }}',
                type: 'POST',
                headers: {
                    'X-HTTP-Method-Override': 'GET'
                }
            },
            columns: [
                {data: 'title', name: 'title'},
                {data: 'description', name: 'description'},
                {data: 'permissions', name: 'permissions'},
                {defaultContent: "", data: "action", name: "action", title: "Action", "orderable": false, "searchable": false}
            ]
        });
    }
  </script>
@endpush
