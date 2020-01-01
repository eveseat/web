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
            @if(auth()->user()->hasSuperUser())
              <button type="submit" class="btn btn-sm btn-danger float-right" form="delete-squad">
                <i class="fas fa-trash-alt"></i>
                Delete
              </button>
            @endif
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
              Candidates <span class="badge badge-pill badge-primary">{{ $squad->applications_count }}</span>
            </li>
            <li class="list-group-item flex-fill border-0">
              Members <span class="badge badge-pill badge-light">{{ $squad->members_count }}</span>
            </li>
            <li class="list-group-item flex-fill border-0">
              Moderators <span class="badge badge-pill badge-warning">{{ $squad->moderators_count }}</span>
            </li>
          </ul>

          <h3>Moderators</h3>

          <hr/>

          @foreach($squad->moderators->chunk(6) as $row)
            <div class="row mt-3">
              @foreach($row as $moderator)
                <div class="col-2">
                  @include('web::partials.character', ['character' => $moderator->main_character])
                  @if(auth()->user()->hasSuperUser())
                    <form method="post" action="{{ route('squads.moderators.remove', ['id' => $squad->id, 'user_id' => $moderator->id]) }}" class="float-right">
                      {!! csrf_field() !!}
                      {!! method_field('DELETE') !!}
                      <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash-alt"></i>
                        Remove
                      </button>
                    </form>
                  @endif
                </div>
              @endforeach
            </div>
          @endforeach

          @if(auth()->user()->hasSuperUser())
            <form method="post" action="{{ route('squads.moderators.add', $squad->id) }}" class="mt-3">
              {!! csrf_field() !!}
              <div class="form-row align-items-center">
                <div class="col-3 offset-8">
                  <label for="squad-moderator" class="sr-only">User</label>
                  <select name="user_id" class="form-control" id="squad-moderator" style="width: 100%;"></select>
                </div>
                <div class="col-auto col-1">
                  <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add
                  </button>
                </div>
              </div>
            </form>
            <form method="post" action="{{ route('squads.destroy', $squad->id) }}" id="delete-squad">
              {!! csrf_field() !!}
              {!! method_field('DELETE') !!}
            </form>
          @endif
        </div>
        @if($squad->type != 'auto' && auth()->user()->name !== 'admin')
          <div class="card-footer">
            @if($squad->is_member)
              <form method="post" action="{{ route('squads.members.leave', $squad->id) }}" id="form-leave">
                {!! csrf_field() !!}
                {!! method_field('DELETE') !!}
              </form>
              <button type="submit" class="btn btn-sm btn-danger float-right" form="form-leave">
                <i class="fas fa-sign-out-alt"></i> {{ trans('web::squads.leave') }}
              </button>
            @else
              @if(! $squad->is_candidate && $squad->isEligible(auth()->user()))
                <button data-toggle="modal" data-target="#application-create" class="btn btn-sm btn-success float-right">
                  <i class="fas fa-sign-in-alt"></i> {{ trans('web::squads.join') }}
                </button>
              @endif
            @endif
          </div>
        @endif
      </div>
    </div>
  </div>

  @if(auth()->user()->hasSuperUser())
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Roles & Constraints</h3>
            <div class="card-tools">
              <div class="input-group input-group-sm">
                @include('web::components.filters.buttons.filters', ['rules' => $squad->filters])
              </div>
            </div>
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
        </div>
      </div>
    </div>
  @endif

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Members</h3>
        </div>
        <div class="card-body">
          @if($squad->is_member || auth()->user()->hasSuperUser())
            {!! $dataTable->table() !!}
          @else
            <p class="text-center">You are not member of that squad.</p>
          @endif
        </div>
      </div>
    </div>
  </div>

  @if($squad->is_moderator || auth()->user()->hasSuperUser())
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

  @include('web::squads.modals.applications.create.application')
  @include('web::squads.modals.applications.read.application')
  @include('web::components.filters.modals.filters.filters', [
    'filters' => [
        (object) ['name' => 'character', 'src' => route('fastlookup.characters'), 'path' => 'characters', 'field' => 'character_infos.character_id', 'label' => 'Character'],
        (object) ['name' => 'corporation', 'src' => route('fastlookup.corporations'), 'path' => 'characters.affiliation', 'field' => 'corporation_id', 'label' => 'Corporation'],
        (object) ['name' => 'alliance', 'src' => route('fastlookup.alliances'), 'path' => 'characters.affiliation', 'field' => 'alliance_id', 'label' => 'Alliance'],
        (object) ['name' => 'skill', 'src' => route('fastlookup.skills'), 'path' => 'characters.skills', 'field' => 'skill_id', 'label' => 'Skill'],
        (object) ['name' => 'type', 'src' => route('fastlookup.items'), 'path' => 'characters.assets', 'field' => 'type_id', 'label' => 'Item'],
    ],
  ])

@endsection

@push('javascript')
  {!! $dataTable->scripts() !!}

  @include('web::includes.javascript.id-to-name')

  <script>
    ids_to_names();

    $('#squad-moderator')
        .select2({
            placeholder: '{{ trans('web::seat.select_item_add') }}',
            ajax: {
                url: '{{ route('squads.moderators.available', $squad->id) }}',
                dataType: 'json',
                cache: true,
                processResults: function (data, params) {
                    return {
                        results: data.results
                    };
                }
            }
        });

    if (! $.fn.dataTable.isDataTable('#candidates-table')) {
        $('#candidates-table').DataTable({
            dom: 'Bfrtip',
            processing: true,
            serverSide: true,
            order: [[0, 'desc']],
            ajax: '{{ route('squads.candidates', $squad->id) }}',
            columns: [
                {data: 'user.name', name: 'user.name'},
                {data: 'characters', name: 'characters'},
                {data: 'created_at', name: 'created_at'},
                {defaultContent: "", data: "action", name: "action", title: "Action", "orderable": false, "searchable": false}
            ]
        });
    }

    if (! $.fn.dataTable.isDataTable('#roles-table')) {
        $('#roles-table').DataTable({
            dom: 'Bfrtip',
            processing: true,
            serverSide: true,
            order: [[0, 'desc']],
            ajax: '{{ route('squads.roles', $squad->id) }}',
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
