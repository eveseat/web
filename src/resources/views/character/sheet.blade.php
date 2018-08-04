@extends('web::character.layouts.view', ['viewname' => 'sheet'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.sheet'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.sheet'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="row">

    <div class="col-md-6">

      @if(auth()->user()->has('character.skills'))
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">
              {{ trans('web::seat.skills_summary') }}
              @if(auth()->user()->has('character.jobs'))
                <span class="pull-right">
                  <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.skillqueue']) }}"
                     style="color: #000000">
                  <i class="fa fa-refresh" data-toggle="tooltip"
                     title="{{ trans('web::seat.update_skill_queue') }}"></i>
                  </a>
                </span>
              @endif
            </h3>
          </div>
          <div class="panel-body">

            <dl>

              <dt>{{ trans('web::seat.curr_training') }}</dt>
              <dd>
                @if($skill_queue->where('finish_date', '>', carbon())->count() > 0)
                  {{ $skill_queue->where('finish_date', '>', carbon())->first()->type->typeName }} to level
                  <b>{{ $skill_queue->where('finish_date', '>', carbon())->first()->finished_level }}</b>
                @else
                  {{ trans('web::seat.no_skill_training') }}
                @endif
              </dd>

              <dt>{{ trans('web::seat.skill_training_end') }}</dt>
              <dd>
                @if($skill_queue->where('finish_date', '>', carbon())->count() > 0)
                  {{ human_diff(carbon($skill_queue->where('finish_date', '>', carbon())->first()->finish_date)) }}
                  on {{ carbon($skill_queue->where('finish_date', '>', carbon())->first()->finish_date)->toDateString() }}
                  at {{ carbon($skill_queue->where('finish_date', '>', carbon())->first()->finish_date)->toTimeString() }}
                @else
                  {{ trans('web::seat.no_skill_training') }}
                @endif
              </dd>

              <dt>{{ trans('web::seat.skill_queue') }}</dt>
              <dd>
                @if($skill_queue && count($skill_queue) > 0)
                  <ol>

                    @foreach($skill_queue->where('finish_date', '>', carbon())->slice(1)->all() as $skill)

                      <li>
                        <span class="col-md-8" data-toggle="tooltip" title=""
                              @if($skill->finish_date != '0000-00-00 00:00:00')
                              data-original-title="Ends {{ human_diff(carbon($skill->finish_date)->toDateString()) }} on {{ carbon($skill->finish_date)->toDateString() }} at {{ carbon($skill->finish_date)->toTimeString() }}"
                            @endif>{{ $skill->type->typeName }}</span>
                        <span class="col-md-4">
                          @for($i = 1; $i <= $skill->finished_level; $i++)
                            @if($i == $skill->finished_level)
                              <span class="fa fa-star text-green"></span>
                            @else
                              <span class="fa fa-star"></span>
                            @endif
                          @endfor
                        </span>
                      </li>

                    @endforeach

                  </ol>
                @else
                  {{ trans('web::seat.empty_skill_queue') }}
                @endif
              </dd>

            </dl>

          </div>
          <div class="panel-footer">
            {{ count($skill_queue) }} {{ trans_choice('web::seat.skill', count($skill_queue)) }}
          </div>
        </div>
      @endif

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::seat.jump_fatigue') }}
            &amp; {{ trans_choice('web::seat.jump_clones', 0) }}</h3>
        </div>
        <div class="panel-body">

          <dl>
            <dt>{{ trans('web::seat.jump_fatigue') }}</dt>
            <dd>

              @if(!is_null($fatigue) && carbon($fatigue->jump_fatigue_expire_date)->gt(carbon(null)))
                {{ $fatigue->jump_fatigue_expire_date }}
                <span class="pull-right">Ends approx {{ human_diff($fatigue->jump_fatigue_expire_date) }}</span>
              @else
                None
              @endif

            </dd>

            <dt>{{ trans('web::seat.jump_act_timer') }}</dt>
            <dd>
              @if(!is_null($last_jump) && carbon($last_jump->last_clone_jump_date)->gt(carbon(null)))
                {{ $last_jump->last_clone_jump_date }}
                <span class="pull-right">Ends approx {{ human_diff($last_jump->last_clone_jump_date) }}</span>
              @else
                {{ trans('web::seat.none') }}
              @endif
            </dd>

            <dt>{{ trans_choice('web::seat.jump_clones', 0) }}</dt>
            <dd>

              @if(count($jump_clones) > 0)

                <ul>

                  @foreach($jump_clones as $clone)
                    <li>
                        @if(! is_null($clone->name))
                        ({{ $clone->name }})
                        @endif

                        @if(! is_null($clone->location))
                        Located at <b>{{ $clone->location->name }}</b>
                        @else
                        Location is unknown
                        @endif
                    </li>
                  @endforeach

                </ul>

              @else
                {{ trans('web::seat.no_jump_clones') }}
              @endif

            </dd>

          </dl>

        </div>
        <div class="panel-footer">
          {{ count($jump_clones) }} {{ trans_choice('web::seat.jump_clones', count($jump_clones)) }}
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans_choice('web::seat.implants', 0) }}</h3>
        </div>
        <div class="panel-body">

          @if(count($implants) > 0)

            <ul>

              @foreach($implants as $implant)
                <li>{{ $implant->type->typeName }}</li>
              @endforeach

            </ul>

          @else
            {{ trans('web::seat.no_implants') }}
          @endif

        </div>
        <div class="panel-footer">
          {{ count($implants) }} {{ trans_choice('web::seat.implants', count($implants)) }}
        </div>
      </div>

    </div>

    <div class="col-md-6">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">
            {{ trans('web::seat.employment_history') }}
            @if(auth()->user()->has('character.jobs'))
              <span class="pull-right">
                <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.corphistory']) }}"
                   style="color: #000000">
                  <i class="fa fa-refresh" data-toggle="tooltip"
                     title="{{ trans('web::seat.update_corp_history') }}"></i>
                </a>
              </span>
            @endif
          </h3>
        </div>
        <div class="panel-body">

          @if(count($employment) > 0)
            <ul class="list-unstyled">

              @foreach($employment as $history)

                <li>
                  {!! img('corporation', $history->corporation_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
                  <b><span rel="id-to-name">{{ $history->corporation_id }}</span></b>
                  on {{ carbon($history->start_date)->toDateString() }}
                  <span class="pull-right">
                 {{ human_diff($history->start_date) }}
                </span>
                </li>

              @endforeach

            </ul>
          @else
            {{ trans('web::seat.no_employment_information') }}
          @endif

        </div>
        <div class="panel-footer">
          {{ count($employment) }} {{ trans_choice('web::seat.corporation', count($employment)) }}
        </div>
      </div>

    </div>

    <!-- character attributes -->
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans_choice('web::seat.attribute', 2) }}</h3>
        </div>
        <div class="panel-body">

          <div class="row">

            <div class="col-md-6">
              <ul class="list-unstyled">
                <li>Charisma: {{ $attributes->charisma }}</li>
                <li>Intelligence: {{ $attributes->intelligence }}</li>
                <li>Memory: {{ $attributes->memory }}</li>
                <li>Perception: {{ $attributes->perception }}</li>
                <li>Willpower: {{ $attributes->willpower }}</li>
              </ul>
            </div>

            <div class="col-md-6">
              <dl>
                <dt>{{ trans('web::seat.bonus_remaps') }}</dt>
                <dd>{{ $attributes->bonus_remaps }}</dd>
                <dt>{{ trans('web::seat.last_remap_date') }}</dt>
                <dd>{{ $attributes->last_remap_date }}</dd>
                <dt>{{ trans('web::seat.accrued_remap_cooldown_date') }}</dt>
                <dd>{{ $attributes->accrued_remap_cooldown_date }}</dd>
              </dl>
            </div>

          </div>

        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans_choice('web::seat.corporation_titles', 0) }}</h3>
        </div>
        <div class="panel-body">
          @if(count($titles) > 0)
            <ul class="list-unstyled">
              @foreach($titles as $title)
                <li>{!! clean_ccp_html($title->name) !!}</li>
              @endforeach
            </ul>
          @else
            {{ trans('no_corporation_titles') }}
          @endif
        </div>
        <div class="panel-footer">
          {{ count($titles) }} {{ trans_choice('web::seat.corporation_titles', count($titles)) }}
        </div>
      </div>
    </div>

  </div>

@stop

@push('javascript')
  @include('web::includes.javascript.id-to-name')
@endpush
