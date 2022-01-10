<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">{{ trans('web::seat.description') }}</h3>
    </div>
    <div class="card-body">

        {!! clean_ccp_html($sheet->description) !!}

    </div>
</div>