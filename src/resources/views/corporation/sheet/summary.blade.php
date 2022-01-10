<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ trans('web::seat.summary') }}</h3>
    </div>
    <div class="card-body">

        <dl>
            <dt><i class="far fa-handshake"></i> {{ trans('web::seat.shares') }}</dt>
            <dd>{{ $sheet->shares }}</dd>

            <dt><i class="fas fa-users"></i> {{ trans('web::seat.member_capacity') }}</dt>
            <dd>{{ $sheet->member_count }} {{ trans_choice('web::seat.member', $sheet->member_count) }}</dd>
            @can('corporation.tracking', $sheet)
                <dt><i class="far fa-address-book"></i> {{ trans('web::seat.tracking') }}</dt>
                <dd>{{ $trackings }} / {{ $sheet->member_count }} ({{ number_format($trackings/$sheet->member_count * 100, 2) }}%) {{ trans_choice('web::seat.valid_token', $sheet->member_count) }}</dd>
            @endcan
        </dl>

    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ trans('web::seat.description') }}</h3>
    </div>
    <div class="card-body">

        {!! clean_ccp_html($sheet->description) !!}

    </div>
</div>