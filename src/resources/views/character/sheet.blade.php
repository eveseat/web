@extends('web::character.layouts.view', ['viewname' => 'sheet', 'breadcrumb' => trans('web::seat.sheet')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.sheet'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="row">

    <div class="col-md-6">

      @can('character.skill', $character)
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              {{ trans('web::seat.skills_summary') }}
            </h3>
            @if($character->refresh_token)
            <div class="card-tools">
              <div class="input-group input-group-sm">
                @include('web::components.jobs.buttons.update', ['type' => 'character', 'entity' => $character->character_id, 'job' => 'character.skillqueue', 'label' => trans('web::seat.update_skill_queue')])
              </div>
            </div>
            @endif
          </div>
          <div class="card-body">

            <dl>

              <dt>{{ trans('web::seat.curr_training') }}</dt>
              <dd>
                @if($character->skill_queue->where('finish_date', '>', carbon())->isNotEmpty())
                  {{ $character->skill_queue->where('finish_date', '>', carbon())->first()->type->typeName ?? trans('web::seat.unknown') }} to level
                  <b>{{ $character->skill_queue->where('finish_date', '>', carbon())->first()->finished_level }}</b>
                @else
                  {{ trans('web::seat.no_skill_training') }}
                @endif
              </dd>

              <dt>{{ trans('web::seat.skill_training_end') }}</dt>
              <dd>
                @if($character->skill_queue->where('finish_date', '>', carbon())->isNotEmpty())
                  {{ human_diff(carbon($character->skill_queue->where('finish_date', '>', carbon())->first()->finish_date)) }}
                  on {{ carbon($character->skill_queue->where('finish_date', '>', carbon())->first()->finish_date)->toDateString() }}
                  at {{ carbon($character->skill_queue->where('finish_date', '>', carbon())->first()->finish_date)->toTimeString() }}
                @else
                  {{ trans('web::seat.no_skill_training') }}
                @endif
              </dd>

              <dt>{{ trans('web::seat.skill_queue') }}</dt>
              <dd>
                @if($character->skill_queue->isNotEmpty())
                  <ol class="skill-list">

                    @foreach($character->skill_queue->where('finish_date', '>', carbon())->slice(1)->all() as $skill)

                      <li>
                        <span class="col-md-8" data-toggle="tooltip" title=""
                              @if($skill->finish_date != '0000-00-00 00:00:00')
                              data-original-title="Ends {{ human_diff(carbon($skill->finish_date)->toDateString()) }} on {{ carbon($skill->finish_date)->toDateString() }} at {{ carbon($skill->finish_date)->toTimeString() }}"
                            @endif>{{ $skill->type->typeName ?? trans('web::seat.unknown') }}</span>
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
          <div class="card-footer">
            {{ $character->skill_queue->count() }} {{ trans_choice('web::seat.skill', $character->skill_queue->count()) }}
          </div>
        </div>
      @endcan

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">{{ trans('web::seat.jump_fatigue') }}
              &amp; {{ trans_choice('web::seat.jump_clones', 0) }}</h3>
          </div>
          <div class="card-body">

            <dl>
              <dt>{{ trans('web::seat.jump_fatigue') }}</dt>
              <dd>

                @if(carbon($character->fatigue->jump_fatigue_expire_date)->gt(carbon(null)))
                  {{ $character->fatigue->jump_fatigue_expire_date }}
                  <span class="float-right">Ends approx {{ human_diff($character->fatigue->jump_fatigue_expire_date) }}</span>
                @else
                  None
                @endif

              </dd>

              <dt>{{ trans('web::seat.jump_act_timer') }}</dt>
              <dd>
                @if(carbon($character->clone->last_clone_jump_date)->gt(carbon(null)))
                  {{ $character->clone->last_clone_jump_date }}
                  <span class="float-right">Ends approx {{ human_diff($character->clone->last_clone_jump_date) }}</span>
                @else
                  {{ trans('web::seat.none') }}
                @endif
              </dd>

              <dt>{{ trans_choice('web::seat.jump_clones', 0) }}</dt>
              <dd>

                @if($character->jump_clones->isNotEmpty())

                  <ul>

                    @foreach($character->jump_clones as $clone)
                      <li>
                        @if(! is_null($clone->name) && ! empty($clone->name))
                          ({{ $clone->name }})
                        @endif

                        @if(! is_null($clone->location))
                          Located at <b>{{ $clone->location->name }}</b>
                        @else
                          Location is unknown
                        @endif

                        @if(! empty($clone->implants))
                          </br>&nbsp&nbsp
                          @foreach($clone->implants as $implant_id)
                            <i data-toggle="tooltip" title="{{ $jumpclone_implants[$implant_id] }}">
                              {!! img('types', 'icon', $implant_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                            </i>
                          @endforeach
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
          <div class="card-footer">
            {{ $character->jump_clones->count() }} {{ trans_choice('web::seat.jump_clones', $character->jump_clones->count()) }}
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">{{ trans_choice('web::seat.attribute', 2) }}</h3>
          </div>
          <div class="card-body">

            <div class="row">

              <div class="col-md-6">
                <dl class="row">
                  <dt class="col-10">Perception</dt>
                  <dd class="col-2">
                    <span class="badge badge-success">{{ $character->pilot_attributes->perception ?: 0 }}</span>
                  </dd>

                  <dt class="col-10">Memory</dt>
                  <dd class="col-2">
                    <span class="badge bg-purple">{{ $character->pilot_attributes->memory ?: 0 }}</span>
                  </dd>

                  <dt class="col-10">Willpower</dt>
                  <dd class="col-2">
                    <span class="badge badge-danger">{{ $character->pilot_attributes->willpower ?: 0 }}</span>
                  </dd>

                  <dt class="col-10">Intelligence</dt>
                  <dd class="col-2">
                    <span class="badge badge-info">{{ $character->pilot_attributes->intelligence ?: 0 }}</span>
                  </dd>

                  <dt class="col-10">Charisma</dt>
                  <dd class="col-2">
                    <span class="badge badge-warning">{{ $character->pilot_attributes->charisma ?: 0 }}</span>
                  </dd>
                </dl>
              </div>

              <div class="col-md-6">
                <dl>
                  <dt>{{ trans('web::seat.bonus_remaps') }}</dt>
                  <dd>{{ $character->pilot_attributes->bonus_remaps ?: 0 }}</dd>
                  <dt>{{ trans('web::seat.last_remap_date') }}</dt>
                  <dd>{{ $character->pilot_attributes->last_remap_date ?: trans('web::seat.no_remap') }}</dd>
                  <dt>{{ trans('web::seat.accrued_remap_cooldown_date') }}</dt>
                  <dd>{{ $character->pilot_attributes->accrued_remap_cooldown_date ?: carbon() }}</dd>
                </dl>
              </div>

            </div>

          </div>
        </div>

    </div>

    <div class="col-md-6">

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            {{ trans('web::seat.employment_history') }}
          </h3>
          @if($character->refresh_token)
          <div class="card-tools">
            <div class="input-group input-group-sm">
              @include('web::components.jobs.buttons.update', ['type' => 'character', 'entity' => $character->character_id, 'job' => 'character.corphistory', 'label' => trans('web::seat.update_corp_history')])
            </div>
          </div>
          @endif
        </div>
        <div class="card-body overflow-auto" style="max-height: 200px;">

          @if($character->corporation_history->isNotEmpty())
            <ul class="list-unstyled">

              @foreach($character->corporation_history as $history)

                <li>
                  {!! img('corporations', 'logo', $history->corporation_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                  <b><span class="id-to-name"
                           data-id="{{ $history->corporation_id }}">{{ trans('web::seat.unknown') }}</span></b>
                  on {{ carbon($history->start_date)->toDateString() }}
                  <span class="float-right">
                 {{ human_diff($history->start_date) }}
                </span>
                </li>

              @endforeach

            </ul>
          @else
            {{ trans('web::seat.no_employment_information') }}
          @endif

        </div>
        <div class="card-footer">
          {{ $character->corporation_history->count() }} {{ trans_choice('web::seat.corporation', $character->corporation_history->count()) }}
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ trans_choice('web::seat.implants', 0) }}</h3>
        </div>
        <div class="card-body">

          @if($character->implants->isNotEmpty())

            <ul>

              @foreach($character->implants as $implant)
                <li>{{ $implant->type->typeName ?? trans('web::seat.unknown') }}</li>
              @endforeach

            </ul>

          @else
            {{ trans('web::seat.no_implants') }}
          @endif

        </div>
        <div class="card-footer">
          {{ $character->implants->count() }} {{ trans_choice('web::seat.implants', $character->implants->count()) }}
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ trans_choice('web::seat.corporation_titles', 0) }}</h3>
        </div>
        <div class="card-body">
          @if($character->titles->isNotEmpty())
            <ul class="list-unstyled">
              @foreach($character->titles as $title)
                <li>{!! clean_ccp_html($title->name) !!}</li>
              @endforeach
            </ul>
          @else
            {{ trans('web::seat.no_corporation_titles') }}
          @endif
        </div>
        <div class="card-footer">
          {{ $character->titles->count() }} {{ trans_choice('web::seat.corporation_titles', $character->titles->count()) }}
        </div>
      </div>

    </div>

  </div>

@stop

@push('head')
  <style>
    .skill-list {
      overflow: auto;
      max-height: 300px;
      padding-inline-start: 30px;
      white-space: nowrap;
    }
  </style>
@endpush

@push('javascript')
  @include('web::includes.javascript.id-to-name')
@endpush
