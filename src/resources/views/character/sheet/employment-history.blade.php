<div class="card mb-3">
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