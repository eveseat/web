<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ trans('web::seat.summary') }}</h3>
    </div>
    <div class="card-body">

        <dl>

            <dt><i class="fas fa-building"></i> {{ trans('web::seat.corporation_count') }}</dt>
            <dd>{{ $sheet->members->count()}} {{ trans_choice('web::seat.corporation', $sheet->members->count()) }}</dd>

            <dt><i class="fas fa-users"></i> {{ trans_choice('web::seat.member', 2) }}</dt>
            <dd>
                {{ $sheet->character_count() }} {{ trans_choice('web::seat.member', $sheet->character_count()) }}
                @if($alliance->members->count() > $alliance->corporations->count())
                    <span class="text-warning">
                  <i class="fas fa-exclamation-triangle" data-bs-toggle="tooltip" title="This number may be inaccurate. SeAT is missing {{ $alliance->members->count() - $alliance->corporations->count() }} member corporations."></i>
                </span>
                @endif
            </dd>

            @can('alliance.tracking', $sheet)
                <dt><i class="far fa-address-book"></i> {{ trans('web::seat.tracking') }}</dt>
                <dd>
                    @if($alliance->character_count() > 0)
                        {{ $tracked }} / {{ $alliance->character_count() }} ({{ number_format($tracked/$alliance->character_count() * 100, 2) }}%) {{ trans_choice('web::seat.valid_token', $alliance->character_count()) }}
                    @else
                        0 / 0 (0.00%) {{ trans_choice('web::seat.valid_token', 2) }}
                    @endif
                </dd>
            @endcan
        </dl>

    </div>
</div>