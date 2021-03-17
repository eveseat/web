<div class="card card-solid">

    <div class="card-header with-border">
        <h3 class="card-title">
            <i class="fas fa-comments"></i>
            Contacts
        </h3>
    </div>
    <div class="card-body">
        <p>
            Have a question ? Want to say thank you ? Need to express your opinion on SeAT ?<br/>
            Find us on Discord !
        </p>
        <p>
            Alternatively, you also can track the conversation on the official <a href="https://forums.eveonline.com/default.aspx?g=posts&t=460658&find=unread">EVE Online Forums</a>.
        </p>
        <p>
            Do you have a bug to report ? Please use our Github issue board <a href="https://github.com/eveseat/seat/issues">here</a>.
        </p>
    </div>

</div>

<div class="row">
    <div class="col-md-4 col-sm-4 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-purple"><i class="fab fa-discord"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Discord</span>
                <span class="info-box-number">{{ $discord_widget->presence_count }} active members</span>
                <a href="{{ $discord_widget->instant_invite }}" target="_blank">Join us !</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-4 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-book"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Documentation</span>
                <span class="info-box-number">Updated {{ human_diff($documentation_widget->updated_at) }}</span>
                <a href="{{ $documentation_widget->url }}" target="_blank">Read me !</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-4 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-black"><i class="fab fa-github"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Github</span>
                <span class="info-box-number">{{ $github_widget->open_issues }} Issues</span>
                <a href="{{ $github_widget->url }}" target="_blank">Contribute !</a>
            </div>
        </div>
    </div>
</div>