@extends('web::character.layouts.view', ['viewname' => 'sheet'])

@section('title', ucfirst(trans_choice('web::character.character', 1)) . ' sheet')
@section('page_header', ucfirst(trans_choice('web::character.character', 1)) . ' sheet')

@section('character_content')

  <div class="row">

    <div class="col-md-6">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Skills Summary</h3>
        </div>
        <div class="panel-body">

          <dl>

            <dt>Currently Training</dt>
            <dd>
              @if($skill_in_training && strlen($skill_in_training->typeName) > 0)
                {{ $skill_in_training->typeName }} to level <b>{{ $skill_in_training->trainingToLevel }}</b>
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
                <ol>

                  @foreach($skill_queue as $skill)

                    <li data-toggle="tooltip" title=""
                        @if($skill->endTime != '0000-00-00 00:00:00')
                          data-original-title="Ends {{ human_diff($skill->endTime) }} at {{ $skill->endTime }}"
                        @endif
                    >
                      {{ $skill->typeName }} {{ $skill->level }}
                    </li>

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

          <dl>

            <dt>Key ID</dt>
            <dd>{{ $account_info->keyID }}</dd>

            <dt>Paid Until</dt>
            <dd>{{ $account_info->paidUntil }} ( payment due {{ human_diff($account_info->paidUntil) }} )</dd>

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

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Jump Fatigue &amp; Jump Clones</h3>
        </div>
        <div class="panel-body">

          <dl>
            <dt>Jump Fatigue</dt>
            <dd>

              @if(carbon($character_sheet->jumpFatigue)->gt(carbon(null)))
                {{ $character_sheet->jumpFatigue }}
                <span class="pull-right">Ends approx {{ human_diff($character_sheet->jumpFatigue) }}</span>
              @else
                None
              @endif

            </dd>

            <dt>Jump Activation Timer</dt>
            <dd>
              @if(carbon($character_sheet->jumpActivation)->gt(carbon(null)))
                {{ $character_sheet->jumpActivation }}
                <span class="pull-right">Ends approx {{ human_diff($character_sheet->jumpActivation) }}</span>
              @else
                None
              @endif
            </dd>

            <dt>Jump Clones</dt>
            <dd>

              @if(count($jump_clones) > 0)

                <ul>

                  @foreach($jump_clones as $clone)

                    <li>
                      <i>{{ $clone->typeName }}</i> located at <b>{{ $clone->location }}</b>
                    </li>

                  @endforeach

                </ul>

              @else
                No Jump Clones
              @endif

            </dd>

          </dl>

        </div>

      </div>

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Implants</h3>
        </div>
        <div class="panel-body">

          @if(count($implants) > 0)

            <ul>

              @foreach($implants as $implant)
                <li>{{ $implant->typeName }}</li>
              @endforeach

            </ul>

          @else
            No Implants
          @endif

        </div>
        <div class="panel-footer">
          {{ count($implants) }} implants
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
                <b>{{ $history->corporationName  }}</b> on {{ carbon($history->startDate)->toDateString() }}
                <span class="pull-right">
                 {{ human_diff($history->startDate) }}
                </span>
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
