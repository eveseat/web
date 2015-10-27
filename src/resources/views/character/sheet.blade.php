@extends('web::character.layouts.view', ['viewname' => 'sheet'])

@section('character_content')

  <div class="row">

    <div class="col-md-6">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Skills Summary</h3>
        </div>
        <div class="panel-body">

          <dl class="dl-horizontal">

            <dt>Currently Training</dt>
            <dd>
              @if($skill_in_training && strlen($skill_in_training->typeName) > 0)
                {{ $skill_in_training->typeName }}
              @else
                No skill in training.
              @endif
            </dd>

            <dt>Skill Training End</dt>
            <dd>
              @if($skill_in_training)
                {{ human_diff($skill_in_training->trainingEndTime) }} at {{ $skill_in_training->trainingEndTime }}
              @else
                No skill in training.
              @endif
            </dd>

            <dt>Skill Queue</dt>
            <dd>
              @if($skill_queue && count($skill_queue) > 0)
                <ol class="list-unstyled">

                  @foreach($skill_queue as $skill)

                    <li data-toggle="tooltip" title=""
                        data-original-title="Ends {{ human_diff($skill->endTime) }} at {{ $skill->endTime }}">
                      {{ $skill->typeName }} {{ $skill->level }}</li>

                  @endforeach

                </ol>
              @else
                The skill queue us empty.
              @endif
            </dd>

          </dl>

        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Account Information</h3>
        </div>
        <div class="panel-body">

          <dl class="dl-horizontal">

            <dt>Key ID</dt>
            <dd>{{ $account_info->keyID }}</dd>

            <dt>Paid Until</dt>
            <dd>{{ $account_info->paidUntil }} | Due {{ human_diff($account_info->paidUntil) }}</dd>

            <dt>Logon Count</dt>
            <dd>{{ $account_info->logonCount }} logins to Eve related services</dd>

            <dt>Online Time</dt>
            <dd>
              {{ $account_info->logonMinutes }} minutes,
              {{ round(((int)$account_info->logonMinutes/60),0) }} hours or
              {{ round(((int)$account_info->logonMinutes/60)/24,0) }} days
            </dd>

          </dl>

        </div>
      </div>

    </div>

    <div class="col-md-6">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Exployment History</h3>
        </div>
        <div class="panel-body">

          <ul class="list-unstyled">

            @foreach($employment as $history)

              <li>
                {!! img('corporation', $history->corporationID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
                {{ $history->corporationName }} {{ human_diff($history->startDate) }}
              </li>

            @endforeach

          </ul>

        </div>
        <div class="panel-footer">
          {{ count($employment) }} corporations
        </div>
      </div>

    </div>

  </div>

@stop
