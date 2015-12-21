<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{ trans('web::seat.summary') }}</h3>
  </div>
  <div class="panel-body">

    <div class="box-body box-profile">

      {!! img('character', $summary->characterID, 128, ['class' => 'profile-user-img img-responsive img-circle']) !!}
      <h3 class="profile-username text-center">
        {{ $summary->characterName }}
      </h3>

      <p class="text-muted text-center">
        {{ $summary->corporationName }}
      </p>

      <table class="table table-condensed table-hover">
        <tbody>

        @foreach($characters as $character)

          @if($character->characterName != $summary->characterName)

            <tr>
              <td>

                <a href="{{ route('character.view.sheet', ['character_id' => $character->characterID]) }}">
                  {!! img('character', $character->characterID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  {{ $character->characterName }}
                </a>

                <span class="text-muted pull-right">
                  {{ $character->corporationName }}
                </span>
              </td>
            </tr>

          @endif

        @endforeach

        </tbody>
      </table>

      <hr>

      <dl>

        <dt>{{ trans('web::seat.joined_curr_corp') }}</dt>
        <dd>{{ human_diff($summary->corporationDate) }}</dd>

        <dt>{{ trans_choice('web::seat.skillpoint', 2) }}</dt>
        <dd>{{ number($summary->skillPoints, 0) }}</dd>

        <dt>{{ trans('web::seat.account_balance') }}</dt>
        <dd>{{ number($summary->accountBalance) }}</dd>

        <dt>{{ trans('web::seat.current_ship') }}</dt>
        <dd>{{ $summary->shipTypeName }} called <i>{{ $summary->shipName }}</i></dd>

        <dt>{{ trans('web::seat.last_location') }}</dt>
        <dd>{{ $summary->lastKnownLocation }}</dd>

        <dt>{{ trans('web::seat.security_status') }}</dt>
        <dd>
          @if($summary->securityStatus < 0)
            <span class="text-danger">
              {{ number($summary->securityStatus, 12) }}
            </span>
          @else
            <span class="text-success">
              {{ number($summary->securityStatus, 12) }}
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

        <dt>{{ trans('web::seat.api_detail') }}</dt>
        <dd>
          <a href="{{ route('api.key.detail', ['key_id' => $summary->keyID]) }}" type="button">
            <i class="fa fa-key"></i>
            {{ trans('web::seat.view') }}
          </a>
        </dd>

        <dt></dt>
      </dl>
    </div>

  </div>
  <div class="panel-footer">
    <span class="text-center center-block">
      <a href="http://eveboard.com/pilot/{{ $summary->characterName }}"
         target="_blank">
        <img src="{{ asset('web/img/eveboard.png') }}">
      </a>
      <a href="https://gate.eveonline.com/Profile/{{ $summary->characterName }}"
         target="_blank">
        <img src="{{ asset('web/img/evegate.png') }}">
      </a>
      <a href="https://eve-kill.net/?a=pilot_detail&plt_external_id={{ $summary->characterID }}"
         target="_blank">
        <img src="{{ asset('web/img/evekill.png') }}">
      </a>
      <a href="http://eve-search.com/search/author/{{ $summary->characterName }}"
         target="_blank">
        <img src="{{ asset('web/img/evesearch.png') }}">
      </a>
      <a href="http://evewho.com/pilot/{{ $summary->characterName }}"
         target="_blank">
        <img src="{{ asset('web/img/evewho.png') }}">
      </a>
      <a href="https://zkillboard.com/character/{{ $summary->characterName }}"
         target="_blank">
        <img src="{{ asset('web/img/zkillboard.png') }}">
      </a>
      <a href="http://eve-hunt.net/hunt/{{ $summary->characterName }}"
         target="_blank">
        <img src="{{ asset('web/img/evehunt.png') }}">
      </a>
    </span>
  </div>
</div>
