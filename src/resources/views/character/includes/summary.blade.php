<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{ trans('web::seat.summary') }}</h3>
  </div>
  <div class="panel-body">

    <div class="box-body box-profile">

      {!! img('character', $summary->character_id, 128, ['class' => 'profile-user-img img-responsive img-circle']) !!}
      <h3 class="profile-username text-center">
        {{ $summary->name }}
      </h3>

      <p class="text-muted text-center"><span class="id-to-name"
                                              data-id="{{ $summary->corporation_id }}">{{ trans('web::seat.unknown') }}</span>
      </p>

      <table class="table table-condensed table-hover">
        <thead></thead>
        <tbody>

        @foreach($characters as $character)

          @if($character->name != $summary->name)

            <tr>
              <td>

                <a href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(),
                 array_merge(request()->route()->parameters, ['character_id' => $character->character_id])) }}">
                  {!! img('character', $character->character_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  {{ $character->name }}
                </a>

                <p class="text-muted pull-right"><span class="id-to-name"
                                                       data-id="{{ is_null($character->character) ? 0 : $character->character->corporation_id }}">{{ trans('web::seat.unknown') }}</span>
                </p>
              </td>
            </tr>

          @endif

        @endforeach

        </tbody>
      </table>

      <hr>

      <dl>

        <dt>{{ trans('web::seat.joined_curr_corp') }}</dt>
        <dd>
            @if(!is_null($summary->corporation()))
              {{ human_diff($summary->corporation()->start_date) }}
            @endif
        </dd>

        @if(auth()->user()->has('character.skills'))
          <dt>{{ trans_choice('web::seat.skillpoint', 2) }}</dt>
          <dd>{{ number($summary->skills->sum('skillpoints_in_skill'), 0) }}</dd>
        @endif

        @if(auth()->user()->has('character.journal') || auth()->user()->has('character.transactions'))
          <dt>{{ trans('web::seat.account_balance') }}</dt>
          <dd>
            @if(!is_null($summary->balance))
              {{ number($summary->balance->balance) }}
            @endif
          </dd>
        @endif

      <dt>{{ trans('web::seat.current_ship') }}</dt>
        <dd>{{ $summary->ship->type->typeName }} called <i>{{ $summary->ship->ship_name }}</i></dd>

        <dt>{{ trans('web::seat.last_location') }}</dt>
        <dd>{{ $summary->lastKnownLocation }}</dd>

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

        <dt>{{ trans('web::seat.last_update') }}</dt>
        <dd>
          <span data-toggle="tooltip"
                title="" data-original-title="{{ $summary->updated_at }}">
            {{ human_diff($summary->updated_at) }}
          </span>
        </dd>

      </dl>
    </div>

  </div>
  <div class="panel-footer">
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
