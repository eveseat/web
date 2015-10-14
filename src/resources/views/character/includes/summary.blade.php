<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Summary</h3>
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

        <dt>Joined Current Corp</dt>
        <dd>{{ human_diff($summary->corporationDate) }}</dd>

        <dt>Skillpoints</dt>
        <dd>{{ number($summary->skillPoints, 0) }}</dd>

        <dt>Account Balance</dt>
        <dd>{{ number($summary->accountBalance) }}</dd>

        <dt>Current Ship</dt>
        <dd>{{ $summary->shipTypeName }} called <i>{{ $summary->shipName }}</i></dd>

        <dt>Last Location</dt>
        <dd>{{ $summary->lastKnownLocation }}</dd>

        <dt>Security Status</dt>
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

        <dt></dt>
      </dl>
    </div>

  </div>
</div>
