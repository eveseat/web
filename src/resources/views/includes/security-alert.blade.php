@if(config('app.debug', false))
    @can('global.superuser')
        <div class="alert alert-danger mb-3" role="alert">
            <h4 class="alert-heading"><i class="fas fa-bug"></i> {{ trans('web::seat.critical') }} !</h4>
            <p>{!! trans('web::seat.debug_disclaimer') !!}</p>
        </div>
    @else
        <div class="alert alert-warning mb-3" role="alert">
            <h4 class="alert-heading"><i class="fas fa-bug"></i> {{ trans('web::seat.critical') }} !</h4>
            <p>{!! trans('web::seat.warning_disclaimer') !!}</p>
        </div>
    @endif
@endif