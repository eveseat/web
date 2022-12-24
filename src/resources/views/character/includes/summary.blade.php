<div class="card card-gray card-outline">
  <div class="card-header">
    <h3 class="card-title">{{ trans('web::seat.summary') }}</h3>
    <div class="card-tools">
      @if(! is_null($character->refresh_token))
        @include('web::character.partials.token_status', ['refresh_token' => $character->refresh_token])
        <span class="badge badge-secondary">{{ $character->refresh_token->user->characters->count() }}</span>
      @else
        @include('web::character.partials.token_status', ['refresh_token' => $character->refresh_token])
        <span class="badge badge-secondary">0</span>
      @endif
    </div>
  </div>
  <div class="card-body box-profile">

    <div class="text-center">

      {!! img('characters', 'portrait', $character->character_id, 128, ['class' => 'profile-user-img img-fluid img-circle']) !!}

    </div>
    <h3 class="profile-username text-center">
      {{ $character->name }}
    </h3>

    <p class="text-muted text-center">
      @include('web::partials.corporation', ['corporation' => $character->affiliation->corporation])
    </p>

    <ul class="list-group list-group-unbordered mb-3">
      @if(! is_null($character->refresh_token))
        @can('global.invalid_tokens')
          @foreach($character->refresh_token->user->all_characters()->where('character_id', '<>', $character->character_id)->sortBy('name') as $character_info)

          <li class="list-group-item">

            @if($character_info->refresh_token)
            <a href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(),
            array_merge(request()->route()->parameters, ['character' => $character_info])) }}">
              {!! img('characters', 'portrait', $character_info->character_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $character_info->name }}
            </a>
            @else
            <a href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(),
            array_merge(request()->route()->parameters, ['character' => $character_info])) }}">
              {!! img('characters', 'portrait', $character_info->character_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $character_info->name }}
            </a>
            <button data-toggle="tooltip" title="Invalid Token" class="btn btn-sm btn-link">
                <i class="fa fa-exclamation-triangle text-danger"></i>
              </button>
            @endif

            <span class="id-to-name text-muted float-right" data-id="{{ $character_info->affiliation->corporation_id }}">{{ $character_info->affiliation->corporation->name }}</span>
          </li>

          @endforeach
        @else
          @foreach($character->refresh_token->user->characters->where('character_id', '<>', $character->character_id)->sortBy('name') as $character_info)

          <li class="list-group-item">

            <a href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(),
            array_merge(request()->route()->parameters, ['character' => $character_info])) }}">
              {!! img('characters', 'portrait', $character_info->character_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $character_info->name }}
            </a>

            <span class="id-to-name text-muted float-right" data-id="{{ $character_info->affiliation->corporation_id }}">{{ $character_info->affiliation->corporation->name }}</span>
          </li>

          @endforeach
        @endcan
      @endif

    </ul>

    <dl>

      <dt>{{ trans('web::seat.joined_curr_corp') }}</dt>
      <dd>
          @if(!is_null($character->current_corporation))
            {{ human_diff($character->current_corporation->start_date) }}
          @endif
      </dd>

      @can('character.skill', $character)
        <dt>{{ trans_choice('web::seat.skillpoint', 2) }}</dt>
        <dd>{{ number($character->skills->sum('skillpoints_in_skill'), 0) }}</dd>
      @endcan

      @canany(['character.journal', 'character.transactions'], $character)
        @if(! is_null($character->balance))
          <dt>{{ trans('web::seat.account_balance') }}</dt>
          <dd>{{ number($character->balance->balance) }}</dd>
        @endif
      @endcanany

      @if (! is_null($character->ship) && ! is_null($character->ship->type))
        <dt>{{ trans('web::seat.current_ship') }}</dt>
        <dd>
          @can('character.asset', $character)
            <a href="#" data-toggle="modal" data-target="#ship-detail" data-url="{{ route('character.view.ship', ['character' => $character]) }}"><i class="fas fa-wrench"></i></a>
          @endcan
          {{ $character->ship->type->typeName }} called <i>{{ $character->ship->ship_name }}</i>
        </dd>
      @endif

      @if (! is_null($character->location))
      <dt>{{ trans('web::seat.last_location') }}</dt>
      <dd>@include('web::partials.location', ['location' => $character->location])</dd>
      @endif

      <dt>{{ trans('web::seat.security_status') }}</dt>
      <dd>
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
      @if(! is_null($character->title))
      <dt>{{ trans_choice('web::seat.title', 1) }}</dt>
      <dd>{{ $character->title }}</dd>
      @endif

    </dl>
  </div>
  <div class="card-footer">
    <span class="text-center center-block">
      <a href="http://eveskillboard.com/pilot/{{ $character->name }}"
         target="_blank">
        <img src="{{ asset('web/img/eveskillboard.png') }}">
      </a>
      <a href="https://forums.eveonline.com/u/{{ str_replace(' ', '_', $character->name) }}/summary"
         target="_blank">
        <img src="{{ asset('web/img/evelogo.png') }}">
      </a>
      <a href="http://eve-search.com/search/author/{{ $character->name }}"
         target="_blank">
        <img src="{{ asset('web/img/evesearch.png') }}">
      </a>
      <a href="http://evewho.com/pilot/{{ $character->name }}"
         target="_blank">
        <img src="{{ asset('web/img/evewho.png') }}">
      </a>
      <a href="https://zkillboard.com/character/{{ $character->name }}"
         target="_blank">
        <img src="{{ asset('web/img/zkillboard.png') }}">
      </a>
      <a href="http://eve-prism.com/?view=character&name={{ $character->name }}" target="_blank">
        <img src="{{ asset('web/img/eve-prism.png') }}" />
      </a>
    </span>
    @can('global.superuser')
      @if(! is_null($character->refresh_token))
        <a href="{{ route('configuration.users.edit', $character->refresh_token->user_id) }}" class="btn btn-xs btn-secondary float-right">
          <i class="fas fa-user"></i> {{ trans_choice('web::seat.user', 1) }}
        </a>
      @endif
    @endcan
  </div>
</div>

@include('web::character.includes.modals.fitting.fitting')

@push('javascript')
  @include('web::includes.javascript.id-to-name')
@endpush
