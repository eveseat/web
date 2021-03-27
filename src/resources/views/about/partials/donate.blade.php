<div class="card card-solid">

    <div class="card-header with-border">
        <h3 class="card-title">
            <i class="fas fa-donate"></i>
            @lang('web::about.donate_pane_title')
        </h3>
    </div>
    <div class="card-body">
        <div class="media">
            <img class="mr-3" src="https://images.evetech.net/corporations/98482334/logo?size=128" alt="eveseat.net" width="64" height="64" />
            <div class="media-body">
                <p class="text-justify">
                    @lang('web::about.donate_pane_first_line', [
                        'seat_project' => '<a href="http://github.com/eveseat/seat/" target="_blank">SeAT</a>',
                        'seat_holding' => '<b>eveseat.net</b>',
                    ])
                </p>
                <p class="text-justify">
                    @lang('web::about.donate_pane_second_line', ['icon' => '<i class="fas fa-trophy"></i>'])
                </p>
            </div>
        </div>
    </div>

</div>