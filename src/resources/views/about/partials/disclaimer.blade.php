<div class="card box-solid">

    <div class="card-header with-border">
        <h3 class="card-title">
            <i class="fas fa-building"></i>
            @lang('web::about.disclaimer_pane_title')
        </h3>
    </div>
    <div class="card-body">
        <p class="text-justify">
            @lang('web::about.disclaimer_pane_content', [
                'eve_online' => '<strong><a href="https://www.eveonline.com" target="_blank">EVE Online</a></strong>',
                'ccp_enterprise' => '<strong><a href="https://www.ccpgames.com" target="_blank">CCP hf</a></strong>',
                'seat' => '<strong><a href="http://github.com/eveseat/seat/"  target="_blank">SeAT</a></strong>'
            ])
        </p>
    </div>

</div>
