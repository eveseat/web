@extends('web::character.layouts.view', ['viewname' => 'sheet'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.sheet'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.sheet'))

@section('character_content')

  <div class="row">

    <div class="col-md-6">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::seat.skills_summary') }}</h3>
        </div>
        <div class="panel-body">

          <dl>

            <dt>{{ trans('web::seat.curr_training') }}</dt>
            <dd>
              @if($skill_queue->count() > 0 && strlen($skill_queue->first()->typeName) > 0)
                {{ $skill_queue->first()->typeName }} to level <b>{{ $skill_queue->first()->finished_level }}</b>
              @else
                {{ trans('web::seat.no_skill_training') }}
              @endif
            </dd>

            <dt>{{ trans('web::seat.skill_training_end') }}</dt>
            <dd>
              @if($skill_in_training)
                {{ human_diff($skill_in_training->trainingEndTime) }} at {{ $skill_in_training->trainingEndTime }}
              @else
                {{ trans('web::seat.no_skill_training') }}
              @endif
            </dd>

            <dt>{{ trans('web::seat.skill_queue') }}</dt>
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
                {{ trans('web::seat.empty_skill_queue') }}
              @endif
            </dd>

          </dl>

        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::seat.account_info') }}</h3>
        </div>
        <div class="panel-body">

          @if(!empty($account_info))

            <dl>

              <dt>{{ trans('web::seat.key_id') }}</dt>
              <dd>{{ $account_info->keyID }}</dd>

              <dt>{{ trans('web::seat.paid_until') }}</dt>
              <dd>{{ $account_info->paidUntil }} ( payment due {{ human_diff($account_info->paidUntil) }} )</dd>

              <dt>{{ trans('web::seat.logon_count') }}</dt>
              <dd>{{ $account_info->logonCount }} logins to Eve related services</dd>

              <dt>{{ trans('web::seat.online_time') }}</dt>
              <dd>
                {{ $account_info->logonMinutes }} minutes,
                {{ round(((int)$account_info->logonMinutes/60),0) }} hours or
                {{ round(((int)$account_info->logonMinutes/60)/24,0) }} days
              </dd>

            </dl>

          @else

            <p>{{ trans('web::seat.no_account_info') }}</p>

          @endif

        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::seat.jump_fatigue') }} &amp; {{ trans('web::seat.jump_clones') }}</h3>
        </div>
        <div class="panel-body">

          <dl>
            <dt>{{ trans('web::seat.jump_fatigue') }}</dt>
            <dd>

              @if(carbon($character_sheet->jumpFatigue)->gt(carbon(null)))
                {{ $character_sheet->jumpFatigue }}
                <span class="pull-right">Ends approx {{ human_diff($character_sheet->jumpFatigue) }}</span>
              @else
                None
              @endif

            </dd>

            <dt>{{ trans('web::seat.jump_act_timer') }}</dt>
            <dd>
              @if(carbon($character_sheet->jumpActivation)->gt(carbon(null)))
                {{ $character_sheet->jumpActivation }}
                <span class="pull-right">Ends approx {{ human_diff($character_sheet->jumpActivation) }}</span>
              @else
                {{ trans('web::seat.none') }}
              @endif
            </dd>

            <dt>{{ trans('web::seat.jump_clones') }}</dt>
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
                {{ trans('web::seat.no_jump_clones') }}
              @endif

            </dd>

          </dl>

        </div>

      </div>

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::seat.implants') }}</h3>
        </div>
        <div class="panel-body">

          @if(count($implants) > 0)

            <ul>

              @foreach($implants as $implant)
                <li>{{ $implant->typeName }}</li>
              @endforeach

            </ul>

          @else
            {{ trans('web::seat.no_implants') }}
          @endif

        </div>
        <div class="panel-footer">
          {{ count($implants) }} {{ trans('web::seat.implants') }}
        </div>
      </div>

    </div>

    <div class="col-md-6">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::seat.employment_history') }}</h3>
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
          {{ count($employment) }} {{ trans_choice('web::seat.corporation', count($employment)) }}
        </div>
      </div>

    </div>

    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::seat.corporation_titles') }}</h3>
        </div>
        <div class="panel-body">
          <ul class="list-unstyled">
            @foreach($titles as $title)
              <li>{!! clean_ccp_html($title->titleName) !!}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>

  </div>

@stop
