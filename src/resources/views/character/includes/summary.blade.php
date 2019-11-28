<div class="card card-gray card-outline">
  <div class="card-header">
    <h3 class="card-title">{{ trans('web::seat.summary') }}</h3>
    <div class="card-tools">
      <span class="badge badge-secondary">{{ $characters->count() }}</span>
    </div>
  </div>
  <div class="card-body box-profile">

    <div class="text-center">

      {!! img('characters', 'portrait', $summary->character_id, 128, ['class' => 'profile-user-img img-fluid img-circle']) !!}

    </div>
    <h3 class="profile-username text-center">
      {{ $summary->name }}
    </h3>

    <p class="text-muted text-center">
      @include('web::partials.corporation', ['corporation' => $summary->affiliation->corporation])
    </p>

    <ul class="list-group list-group-unbordered mb-3">
      @foreach($characters as $character)

        @if($character->name != $summary->name)

          <li class="list-group-item">

            <a href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(),
             array_merge(request()->route()->parameters, ['character_id' => $character->character_id])) }}">
              {!! img('characters', 'portrait', $character->character_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $character->name }}
            </a>

            <span class="id-to-name text-muted float-right" data-id="{{ $character->affiliation->corporation_id }}">{{ $character->affiliation->corporation->name }}</span>
          </li>

        @endif

      @endforeach

    </ul>

    <dl>

      <dt>{{ trans('web::seat.joined_curr_corp') }}</dt>
      <dd>
          @if(!is_null($summary->current_corporation))
            {{ human_diff($summary->current_corporation->start_date) }}
          @endif
      </dd>

      @if(auth()->user()->has('character.skills'))
        <dt>{{ trans_choice('web::seat.skillpoint', 2) }}</dt>
        <dd>{{ number($summary->skills->sum('skillpoints_in_skill'), 0) }}</dd>
      @endif

      @if(auth()->user()->has('character.journal') || auth()->user()->has('character.transactions'))
      @if(! is_null($summary->balance))
      <dt>{{ trans('web::seat.account_balance') }}</dt>
      <dd>{{ number($summary->balance->balance) }}</dd>
      @endif
      @endif

      @if (! is_null($summary->ship) && ! is_null($summary->ship->type))
      <dt>{{ trans('web::seat.current_ship') }}</dt>
      <dd>{{ $summary->ship->type->typeName }} called <i>{{ $summary->ship->ship_name }}</i></dd>
      @endif

      @if (! is_null($summary->location))
      <dt>{{ trans('web::seat.last_location') }}</dt>
      <dd>@include('web::partials.location', ['location' => $summary->location])</dd>
      @endif

      <dt>{{ trans('web::seat.security_status') }}</dt>
      <dd>
        @if($summary->security_status < 0)
          <span class="text-danger">
            {{ number($summary->security_status, 12) }}
          </span>
        @else
          <span class="text-success">
            {{ number($summary->security_status, 12) }}
          </span>
        @endif
      </dd>

    </dl>
  </div>
  <div class="card-footer">
    <span class="text-center center-block">
      <a href="http://eveskillboard.com/pilot/{{ $summary->name }}"
         target="_blank">
        <img src="{{ asset('web/img/eveskillboard.png') }}">
      </a>
      <a href="https://forums.eveonline.com/u/{{ str_replace(' ', '_', $summary->name) }}/summary"
         target="_blank">
        <img src="{{ asset('web/img/evelogo.png') }}">
      </a>
      <a href="http://eve-search.com/search/author/{{ $summary->name }}"
         target="_blank">
        <img src="{{ asset('web/img/evesearch.png') }}">
      </a>
      <a href="http://evewho.com/pilot/{{ $summary->name }}"
         target="_blank">
        <img src="{{ asset('web/img/evewho.png') }}">
      </a>
      <a href="https://zkillboard.com/character/{{ $summary->name }}"
         target="_blank">
        <img src="{{ asset('web/img/zkillboard.png') }}">
      </a>
      <a href="http://eve-prism.com/?view=character&name={{ $summary->name }}" target="_blank">
        <img src="{{ asset('web/img/eve-prism.png') }}" />
      </a>
    </span>
  </div>
</div>

@push('javascript')
@include('web::includes.javascript.id-to-name')
@endpush
