<div class="card mb-3">

    <div class="card-header with-border">
        <h3 class="card-title">
            <div class="ribbon ribbon-start bg-dark">
                <i class="fas fa-comments fa-lg"></i>
            </div>
            <span class="ms-4">@lang('web::about.contact_pane_title')</span>
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

<div class="row row-cards">

    <div class="col-md-4 col-sm-4 col-12">
        <div class="card card-sm">
            <div class="row row-0">
                <div class="col-3">
                    <span class="w-100 h-100 object-cover avatar rounded-0 bg-purple text-white"><i class="fab fa-discord fa-2x"></i></span>
                </div>
                <div class="col">
                    <div class="card-body">
                        <div class="font-weight-medium">Discord</div>
                        <div class="text-muted">@lang('web::about.contact_widget_active_members', ['count' => $discord_widget->presence_count])</div>
                    </div>
                </div>
            </div>
            <a href="{{ $discord_widget->instant_invite }}" target="_blank" class="card-btn">@lang('web::about.contact_widget_join_us')</a>
        </div>
    </div>

    <div class="col-md-4 col-sm-4 col-12">
        <div class="card card-sm">
            <div class="row row-0">
                <div class="col-3">
                    <span class="w-100 h-100 object-cover avatar rounded-0 bg-info text-white"><i class="fas fa-book fa-2x"></i></span>
                </div>
                <div class="col">
                    <div class="card-body">
                        <div class="font-weight-medium">@lang('web::about.contact_widget_documentation')</div>
                        <div class="text-muted">@lang('web::about.contact_widget_updated_at', ['date_time' => human_diff($documentation_widget->updated_at)])</div>
                    </div>
                </div>
            </div>
            <a href="{{ $documentation_widget->url }}" target="_blank" class="card-btn">@lang('web::about.contact_widget_read_me')</a>
        </div>
    </div>

    <div class="col-md-4 col-sm-4 col-12">
        <div class="card card-sm">
            <div class="row row-0">
                <div class="col-3">
                    <span class="w-100 h-100 object-cover avatar rounded-0 bg-dark text-white"><i class="fab fa-github fa-2x"></i></span>
                </div>
                <div class="col">
                    <div class="card-body">
                        <div class="font-weight-medium">GitHub</div>
                        <div class="text-muted">@lang('web::about.contact_widget_github_issues', ['count' => $github_widget->open_issues])</div>
                    </div>
                </div>
            </div>
            <a href="{{ $github_widget->url }}" target="_blank" class="card-btn">@lang('web::about.contact_widget_github_contribute')</a>
        </div>
    </div>
</div>
