@extends('web::layouts.grids.12')

@section('title', trans_choice('web::squads.squad', 0))
@section('page_header', trans_choice('web::squads.squad', 1))
@section('page_description', $squad->name)

@push('javascript')
    <script>
        window.LaravelDataTables = window.LaravelDataTables || {};
    </script>
@endpush

@section('full')
  <div class="row row-cards">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
            <h3 class="card-title">Sheet</h3>
            @can('squads.edit', $squad)
            <div class="card-actions">
                <a href="{{ route('seatcore::squads.edit', $squad) }}" class="btn btn-warning d-sm-inline-block">
                    <i class="fas fa-edit"></i>
                    Edit
                </a>
                @can('squads.create')
                    <button type="submit" class="btn btn-danger d-sm-inline-block" form="delete-squad">
                        <i class="fas fa-trash-alt"></i>
                        Delete
                    </button>
                @endcan
            </div>
            @endcan
        </div>

        <div class="card-body">

          @include('web::squads.sheet.partials.summary')

          <hr/>

          @include('web::squads.sheet.partials.metadata')

          <hr/>

          @include('web::squads.sheet.partials.moderators')

        </div>

        @if($squad->type != 'auto' && auth()->user()->name !== 'admin')
          <div class="card-footer">
            @if($squad->is_member)
              <form method="post" action="{{ route('seatcore::squads.members.leave', $squad) }}" id="form-leave">
                {!! csrf_field() !!}
                {!! method_field('DELETE') !!}
              </form>
              <button type="submit" class="btn btn-sm btn-danger d-sm-inline-block float-end" form="form-leave">
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

                  <button type="submit" class="btn btn-sm btn-danger d-sm-inline-block" form="form-cancel">
                    <i class="fas fa-times-circle"></i> {{ trans('web::squads.cancel') }}
                  </button>
                </div>
              @endif
              @if(! $squad->is_candidate && $squad->type == 'manual' && $squad->isEligible(auth()->user()))
                @if($squad->moderators->isEmpty())
                  <form method="post" action="{{ route('seatcore::squads.applications.store', $squad) }}">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-sm btn-success d-sm-inline-block float-end">
                      <i class="fas fa-sign-in-alt"></i> {{ trans('web::squads.join') }}
                    </button>
                  </form>
                @else
                  <button data-bs-toggle="modal" data-bs-target="#application-create" class="btn btn-sm btn-success d-sm-inline-block float-end">
                    <i class="fas fa-sign-in-alt"></i> {{ trans('web::squads.join') }}
                  </button>
                @endif
              @endif
            @endif
          </div>
        @endif
      </div>
    </div>

    @can('squads.manage_roles', $squad)
      @include('web::squads.sheet.partials.roles')
    @endcan

    @can('squads.show_members', $squad)
      @include('web::squads.sheet.partials.members')
    @endcan

    @can('squads.manage_candidates', $squad)
      @include('web::squads.sheet.partials.candidates')
  @endcan

  </div>

  @include('web::squads.modals.applications.create.application')
  @include('web::squads.modals.applications.read.application')

@endsection

@push('javascript')

  @include('web::includes.javascript.id-to-name')

  <script>
    ids_to_names();

    let fieldSelectors = [
        {id: '#squad-moderator', src: '{{ route('seatcore::squads.moderators.lookup', $squad) }}', renderItem: renderLookupCharacterItem, renderOption: renderLookupCharacterOption},
        {id: '#squad-role', src: '{{ route('seatcore::squads.roles.lookup', $squad) }}', renderItem: renderLookupDefaultItem, renderOption: renderLookupDefaultOption},
        {id: '#squad-member', src: '{{ route('seatcore::squads.members.lookup', $squad) }}', renderItem: renderLookupCharacterItem, renderOption: renderLookupCharacterOption},
    ];

    function renderLookupDefaultItem(data, escape) {
        return '<div><span class="badge bg-secondary">' + escape(data.text) + '</span></div>';
    }

    function renderLookupDefaultOption(data, escape) {
        return '<div>' + escape(data.text) + '</div>';
    }

    function renderLookupCharacterItem(data, escape) {
        return '<div><span class="dropdown-item-indicator"><span class="avatar avatar-xs" style="background-image: url(https://images.evetech.net/characters/' + escape(data.main_character_id) + '/portrait?size=32)"></span></span>' + escape(data.text) + '</div>';
    }

    function renderLookupCharacterOption(data, escape) {
        return '<div><span class="dropdown-item-indicator"><span class="avatar avatar-xs" style="background-image: url(https://images.evetech.net/characters/' + escape(data.main_character_id) + '/portrait?size=32)"></span></span>' + escape(data.text) + '</div>';
    }

    fieldSelectors.forEach(field => {
        field.o = new TomSelect(field.id, {
            valueField: 'id',
            labelField: 'text',
            searchField: 'text',
            copyClassesToDropdown: false,
            dropdownClass: 'dropdown-menu',
            optionClass:'dropdown-item',
            maxItems: 1,
            persist: false,
            preload: true,
            openOnFocus: false,
            loadThrottle: null,
            shouldLoad: function (query) {
                return query.length > 0;
            },
            load: function (query, callback) {
                this.clearOptions();

                fetch(field.src + '?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(json => {
                        callback(json.results);
                    })
                    .catch(() => {
                        callback();
                    });
            },
            render: {
                item: field.renderItem,
                option: field.renderOption,
            }
        });
    });

    document.addEventListener('submit', function (e) {
        if (e.target.matches('#squad-member-form, #squad-role-form')) {
            e.preventDefault();

            let form = e.target;

            if (! form.querySelector('select').value)
                return;

            let data = new FormData(form);

            let spinner = document.createElement('span');
            spinner.classList.add('spinner-border');
            spinner.classList.add('spinner-border-sm');
            spinner.setAttribute('role', 'status');
            spinner.setAttribute('aria-hidden', true);

            let button = form.querySelector('.btn-success');
            button.setAttribute('disabled', true);
            button.removeChild(button.querySelector('i'));
            button.insertBefore(spinner, button.firstChild);

            fetch(form.getAttribute('action'), {
                method: form.getAttribute('method'),
                body: data,
            }).then(function () {
                let plus = document.createElement('i');
                plus.classList.add('fas');
                plus.classList.add('fa-plus');

                switch (form.id) {
                    case 'squad-member-form':
                        fieldSelectors[2].o.clear();
                        window.LaravelDataTables.membersTableBuilder.ajax.reload();
                        break;
                    case 'squad-role-form':
                        fieldSelectors[1].o.clear();
                        window.LaravelDataTables.rolesTableBuilder.ajax.reload();
                        break;
                }

                button.removeChild(spinner);
                button.insertBefore(plus, button.firstChild);
                button.removeAttribute('disabled');
            });

            return false;
        }
    });
  </script>
@endpush
