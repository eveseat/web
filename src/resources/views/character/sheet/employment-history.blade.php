<div class="card mb-3" style="height: 28rem">
    <div class="card-header">
        <h3 class="card-title">
            {{ trans('web::seat.employment_history') }}
        </h3>
        @if($character->refresh_token)
            <div class="card-actions btn-actions">
                @include('web::components.jobs.buttons.update', ['type' => 'character', 'entity' => $character->character_id, 'job' => 'character.corphistory', 'label' => trans('web::seat.update_corp_history')])
            </div>
        @endif
    </div>
    <div class="card-body card-body-scrollable card-body-scrollable-shadow">
        <div class="divide-y">
            @if($character->corporation_history->isNotEmpty())
                    @foreach($character->corporation_history as $history)
                        <div>
                            <div class="row">
                                <div class="col-auto">
                                    <span class="avatar">
                                        {!! img('corporations', 'logo', $history->corporation_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="text-truncate">
                                        <strong class="id-to-name" data-id="{{ $history->corporation_id }}">{{ $history->corporation->name ?? trans('web::seat.unknown') }}</strong>
                                    </div>
                                    <div class="text-muted" data-bs-toggle="tooltip" data-bs-placement="left" title="{{ carbon($history->start_date)->toDateString() }}">{{ human_diff($history->start_date) }}</div>
                                </div>
                            </div>
                        </div>

                    @endforeach
            @else
                {{ trans('web::seat.no_employment_information') }}
            @endif
        </div>
    </div>
    <div class="card-footer">
        {{ $character->corporation_history->count() }} {{ trans_choice('web::seat.corporation', $character->corporation_history->count()) }}
    </div>
</div>