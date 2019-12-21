@extends('web::layouts.grids.12')

@section('title', trans_choice('web::squads.squad', 0))
@section('page_header', trans_choice('web::squads.squad', 1))
@section('page_description', $squad->name)

@section('full')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="media">
            <img src="{{ $squad->logo }}" width="128" height="128" class="align-self-center mr-3" />
            <div class="media-body">
              <p>{{ $squad->description }}</p>
            </div>
          </div>
        </div>
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

  @if($squad->type != 'auto')
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="btn btn-group float-right">
              @if(auth()->user()->name !== 'admin')
                @if($squad->isMember())
                  @include('web::squads.buttons.squads.leave')
                @else
                  @if(! $squad->isCandidate() && $squad->isEligible(auth()->user()))
                    @include('web::squads.buttons.squads.join')
                  @endif
                @endif
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif

  <div class="row">
    <div class="col-12">
      <div class="card-deck mb-3">
        <div class="card col-2">
          <div class="card-header">
            <h3 class="card-title">Moderators</h3>
          </div>
          <div class="card-body p-0">
            <ul class="list-group list-group-flush">
              @foreach($squad->moderators as $moderator)
                <li class="list-group-item">
                  @include('web::partials.character', ['character' => $moderator->main_character])
                </li>
              @endforeach
            </ul>
          </div>
        </div>
        <div class="col-10">
          <div class="row">
            <div class="col-12 mb-3">
              <div class="card ml-0 mr-0">
                <div class="card-header">
                  <h3 class="card-title">Members</h3>
                </div>
                <div class="card-body">
                  @if($squad->isMember() || auth()->user()->hasSuperUser())
                    {!! $dataTable->table() !!}
                  @else
                    <p class="text-center">You are not member of that squad.</p>
                  @endif
                </div>
              </div>
            </div>
          </div>

          @if($squad->isModerator() || auth()->user()->hasSuperUser())
            <div class="row">
              <div class="col-12">
                <div class="card ml-0 mr-0">
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

        </div>
      </div>
    </div>
  </div>

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

  <script>
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
