<div class="card mb-3">
  {!! img('characters', 'portrait', $character->character_id, 512, ['class' => 'card-img-top bg-dark bg-gradient']) !!}
  <div class="card-body">
    <h5 class="card-title text-center">
      <span class="align-middle">{{ $character->name }}</span>
      @if(! is_null($character->refresh_token))
        @include('web::character.partials.token_status', ['refresh_token' => $character->refresh_token])
        <span class="badge rounded-pill badge-secondary align-middle">{{ $character->refresh_token->user->characters->count() }}</span>
      @endif
    </h5>
    <!-- affiliation -->
    <div class="list-group list-group-flush list-group-hoverable mb-3">
      <!-- corporation -->
      <div class="list-group-item ps-0 pe-0">
        <div class="row align-items-center">
          <div class="col-auto">
            {!! img('corporations', 'logo', $character->affiliation->corporation_id, 128, ['class' => 'avatar']) !!}
          </div>
          <div class="col">
            <a href="{{ route('seatcore::corporation.view.default', $character->affiliation->corporation_id) }}" class="text-body d-block stretched-link">{{ $character->affiliation->corporation->name }}</a>
            <small class="text-muted mt-n1">Corporation</small>
          </div>
        </div>
      </div>
      <!-- ./corporation -->
      <!-- alliance -->
      @if($character->affiliation->alliance_id)
      <div class="list-group-item ps-0 pe-0">
        <div class="row align-items-center">
          <div class="col-auto">
            {!! img('alliances', 'logo', $character->affiliation->alliance_id, 128, ['class' => 'avatar']) !!}
          </div>
          <div class="col">
            <a href="{{ route('seatcore::alliance.view.default', $character->affiliation->alliance_id) }}" class="text-body d-block stretched-link">{{ $character->affiliation->alliance->name }}</a>
            <small class="text-muted mt-n1">Alliance</small>
          </div>
        </div>
      </div>
      @endif
      <!-- ./alliance -->
      <!-- faction -->
      @if($character->faction_id)
        <div class="list-group-item ps-0 pe-0">
          <div class="row align-items-center">
            <div class="col-auto">
              {!! img('corporations', 'logo', $character->faction_id, 128, ['class' => 'avatar']) !!}
            </div>
            <div class="col">
              <span class="text-body d-block id-to-name" data-id="{{ $character->faction_id }}">{{ trans('web::seat.unknown') }}</span>
              <span class="text-muted mt-n1">Faction</span>
            </div>
          </div>
        </div>
    @endif
    <!-- ./faction -->
    </div>
    <!-- ./affiliation -->
    <!-- information -->
    <dl class="row">
      <dt class="col-5">Age:</dt>
      <dd class="col-7">{{ carbon($character->birthday)->diffInYears() }}</dd>
      <dt class="col-5">Birthday:</dt>
      <dd class="col-7">
        @if(carbon($character->birthday)->isBirthday())
          <i class="fas fa-birthday-cake me-2 text-primary" data-bs-toggle="tooltip" title="This is his birthday !"></i>
        @endif
        {{ carbon($character->birthday)->format('jS \o\f F') }}
      </dd>
      <dt class="col-5">Joined:</dt>
      <dd class="col-7">
        @if(! is_null($character->corporation_history))
          {{ human_diff($character->corporation_history->sortBy('start_date')->last()->start_date) }}
        @endif
      </dd>
      @can('character.skill', $character)
      <dt class="col-5">Skillpoints:</dt>
      <dd class="col-7">{{ number($character->skills->sum('skillpoints_in_skill'), 0) }}</dd>
      @endcan
      @canany(['character.journal', 'character.transactions'], $character)
      <dt class="col-5">Balance:</dt>
      <dd class="col-7">{{ number($character->balance ? $character->balance->balance : 0.0) }}</dd>
      @endcan
      @can('character.asset', $character)
      <dt class="col-5">Ship:</dt>
      <dd class="col-7">
        <a href="#" data-bs-toggle="modal" data-bs-target="#ship-detail" data-url="{{ route('seatcore::character.view.ship', ['character' => $character]) }}"><i class="fas fa-wrench"></i></a>
        {{ $character->ship->ship_name }}
      </dd>
      @endcan
      <dt class="col-5">Location:</dt>
      <dd class="col-7">
        @include('web::partials.location', ['location' => $character->location])
      </dd>
      <dt class="col-5">Security Status:</dt>
      <dd class="col-7">
        @if($character->security_status < 0)
          <span class="text-danger">
            {{ number($character->security_status, 2) }}
          </span>
        @else
          <span class="text-success">
            {{ number($character->security_status, 2) }}
          </span>
        @endif
      </dd>
    </dl>
    <!-- ./information -->
  </div>
  <!-- navigation -->
  <ul class="nav d-flex">
    <li class="nav-item dropdown flex-fill">
      <a href="#" class="card-btn dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">
        <i class="fas fa-wrench me-2"></i>
        Tools
      </a>
      <ul class="dropdown-menu">
        <li>
          <a href="https://eveskillboard.com/pilot/{{ str_replace(' ', '_', $character->name) }}" target="_blank" class="dropdown-item">
            <img src="{{ asset('web/img/eveskillboard.png') }}" class="me-2">
            Eve Skillboard
          </a>
        </li>
        <li>
          <a href="https://forums.eveonline.com/u/{{ str_replace(' ', '_', $character->name) }}/summary" target="_blank" class="dropdown-item">
            <img src="{{ asset('web/img/evelogo.png') }}" class="me-2">
            Eve Online
          </a>
        </li>
        <li>
          <a href="https://eve-search.com/search/author/{{ $character->name }}" target="_blank" class="dropdown-item">
            <img src="{{ asset('web/img/evesearch.png') }}" class="me-2">
            Eve Search !
          </a>
        </li>
        <li>
          <a href="https://evewho.com/character/{{ $character->character_id }}" target="_blank" class="dropdown-item">
            <img src="{{ asset('web/img/evewho.png') }}" class="me-2">
            Eve Who ?
          </a>
        </li>
        <li>
          <a href="https://zkillboard.com/character/{{ $character->character_id }}" target="_blank" class="dropdown-item">
            <img src="{{ asset('web/img/zkillboard.png') }}" class="me-2">
            Z-Killboard
          </a>
        </li>
        <li>
          <a href="http://eve-prism.com/?view=character&name={{ $character->name }}" target="_blank" class="dropdown-item">
            <img src="{{ asset('web/img/eve-prism.png') }}" class="me-2">
            Eve Prism
          </a>
        </li>
      </ul>
    </li>
    @if(! is_null($character->refresh_token) && $character->refresh_token->user->characters->where('character_id', '<>', $character->character_id)->isNotEmpty())
    <li class="nav-item dropdown flex-fill">
      <a href="#" class="card-btn dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">
        <i class="fas fa-users me-2"></i>
        Characters
      </a>
      <ul class="dropdown-menu">
          @foreach($character->refresh_token->user->characters->where('character_id', '<>', $character->character_id)->sortBy('name') as $character_info)
            <li>
                <a href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), array_merge(request()->route()->parameters, ['character' => $character_info])) }}" class="dropdown-item">
                  {!! img('characters', 'portrait', $character_info->character_id, 64, ['class' => 'avatar me-2'], false) !!}
                  {{ $character_info->name }}
                </a>
            </li>
          @endforeach
      </ul>
    </li>
    @endif
    @can('global.superuser')
      @if(! is_null($character->refresh_token))
        <li class="nav-item flex-fill">
          <a href="{{ route('seatcore::configuration.users.edit', $character->refresh_token->user_id) }}" class="card-btn">
            <i class="fas fa-user me-2"></i> {{ trans_choice('web::seat.user', 1) }}
          </a>
        </li>
      @endif
    @endcan
  </ul>
  <!-- ./navigation -->
  <!-- ./card-body -->
</div>

@include('web::character.includes.modals.fitting.fitting')

@push('javascript')
  @include('web::includes.javascript.id-to-name')
@endpush
