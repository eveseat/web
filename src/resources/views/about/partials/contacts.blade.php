<div class="card card-solid">

    <div class="card-header with-border">
        <h3 class="card-title">
            <i class="fas fa-comments"></i>
            @lang('web::about.contact_pane_title')
        </h3>
    </div>
    <div class="card-body">
        <p>
            @lang('web::about.contact_pane_question_discord_first_line')<br/>
            @lang('web::about.contact_pane_question_discord_second_line')
        </p>
        <p>
            @lang('web::about.contact_pane_question_forum', [
                'link' => sprintf('<a href="https://forums.eveonline.com/default.aspx?g=posts&t=460658&find=unread"  target="_blank">%s</a>', trans('web::about.contact_pane_question_eve_online_forums_placeholder'))
            ])
        </p>
        <p>
            @lang('web::about.contact_pane_question_github', [
                'link' => sprintf('<a href="https://github.com/eveseat/seat/issues"  target="_blank">%s</a>', trans('web::about.contact_pane_question_github_here_placeholder')),
            ])
        </p>
    </div>

</div>

<div class="row">
    <div class="col-md-4 col-sm-4 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-purple"><i class="fab fa-discord"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Discord</span>
                <span class="info-box-number">@lang('web::about.contact_widget_active_members', ['count' => $discord_widget->presence_count])</span>
                <a href="{{ $discord_widget->instant_invite }}" target="_blank">@lang('web::about.contact_widget_join_us')</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-4 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-book"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">@lang('web::about.contact_widget_documentation')</span>
                <span class="info-box-number">@lang('web::about.contact_widget_updated_at', ['date_time' => human_diff($documentation_widget->updated_at)])</span>
                <a href="{{ $documentation_widget->url }}" target="_blank">@lang('web::about.contact_widget_read_me')</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-4 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-black"><i class="fab fa-github"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Github</span>
                <span class="info-box-number">@lang('web::about.contact_widget_github_issues', ['count' => $github_widget->open_issues])</span>
                <a href="{{ $github_widget->url }}" target="_blank">@lang('web::about.contact_widget_github_contribute')</a>
            </div>
        </div>
    </div>
</div>
