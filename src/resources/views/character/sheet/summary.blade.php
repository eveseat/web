@can('character.skill', $character)
    <div class="card mb-3">
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
                        {{ $character->skill_queue->where('finish_date', '>', carbon())->first()->type->typeName }} to level
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
                        <span class="col-md-8"
                              @if($skill->finish_date != '0000-00-00 00:00:00')
                              data-bs-toggle="tooltip"
                              title="Ends {{ human_diff(carbon($skill->finish_date)->toDateString()) }} on {{ carbon($skill->finish_date)->toDateString() }} at {{ carbon($skill->finish_date)->toTimeString() }}"
                          @endif
                        >{{ $skill->type->typeName }}</span>
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