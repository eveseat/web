<div class="card">
    <form role="form" action="{{ route('seatcore::seat.update.settings') }}" method="post" class="form-horizontal">
        {{ csrf_field() }}
        <div class="card-header bg-primary-lt">
            <h3 class="card-title">{{ trans('web::seat.settings') }}</h3>
        </div>
        <div class="card-body">
            <legend>{{ trans('web::seat.admin_email') }}</legend>

            <!-- Text input-->
            <div class="form-group row">
                <label class="col-md-4 col-form-label" for="admin_contact">{{ trans('web::seat.admin_email') }}</label>
                <div class="col-md-6">
                    <input id="admin_contact" name="admin_contact" type="email" class="form-control input-md"
                        value="{{ setting('admin_contact', true) }}">
                    <p class="form-text text-muted mb-0">
                        {{ trans('web::seat.admin_email_help') }}
                    </p>
                </div>
            </div>

            <legend>{{ trans_choice('web::settings.jobs', 0) }}</legend>

            <!-- Text input -->
            <div class="form-group row">
                <label class="col-md-4 col-form-label"
                    for="market_prices_region">{{ trans('web::settings.market_prices_region') }}</label>
                <div class="col-md-6">
                    <select id="market_prices_region" name="market_prices_region" class="form-control"
                        style="width: 100%;"></select>
                    <p class="form-text text-muted mb-0">{{ trans('web::settings.market_prices_region_help') }}
                    </p>
                </div>
            </div>

            <legend>{{ trans('web::seat.maintenance') }}</legend>

            <!-- Text input-->
            <div class="form-group row">
                <label class="col-md-4 col-form-label" for="cleanup">{{ trans('web::seat.cleanup_data') }}</label>
                <div class="col-md-6">
                    <select id="cleanup" name="cleanup_data" class="form-control" style="width: 100%;">
                        <option value="yes" @if (setting('cleanup_data', true) == 'yes') selected @endif>
                            {{ trans('web::seat.yes') }}
                        </option>
                        <option value="no" @if (setting('cleanup_data', true) == 'no') selected @endif>
                            {{ trans('web::seat.no') }}
                        </option>
                    </select>
                    <p class="form-text text-muted mb-0">
                        {{ trans('web::seat.cleanup_data_help') }}
                    </p>
                </div>
            </div>

            <legend>{{ trans('web::seat.registration') }}</legend>

            <!-- Select Basic -->
            <div class="form-group row">
                <label class="col-md-4 col-form-label"
                    for="registration">{{ trans('web::seat.allow_registration') }}</label>
                <div class="col-md-6">
                    <select id="registration" name="registration" class="form-control" style="width: 100%;">
                        <option value="yes" @if (setting('registration', true) == 'yes') selected @endif>
                            {{ trans('web::seat.yes') }}
                        </option>
                        <option value="no" @if (setting('registration', true) == 'no') selected @endif>
                            {{ trans('web::seat.no') }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- User Character Unlink -->
            <div class="form-group row">
                <label class="col-md-4 col-form-label"
                    for="allow_user_character_unlink">{{ trans('web::seat.allow_user_character_unlink') }}</label>
                <div class="col-md-6">
                    <select id="allow_user_character_unlink" name="allow_user_character_unlink" class="form-control"
                        style="width: 100%;">
                        <option value="yes" @if (setting('allow_user_character_unlink', true) == 'yes') selected @endif>
                            {{ trans('web::seat.yes') }}
                        </option>
                        <option value="no" @if (in_array(setting('allow_user_character_unlink', true), [null, 'no'])) selected @endif>
                            {{ trans('web::seat.no') }}
                        </option>
                    </select>
                </div>
            </div>

            <legend>{{ trans('web::seat.google_analytics') }}</legend>

            <!-- Select Basic -->
            <div class="form-group row">
                <label class="col-md-4 col-form-label"
                    for="allow_tracking">{{ trans('web::seat.allow_tracking') }}</label>
                <div class="col-md-6">
                    <select id="allow_tracking" name="allow_tracking" class="form-control" style="width: 100%;">
                        <option value="yes" @if (setting('allow_tracking', true) == 'yes') selected @endif>
                            {{ trans('web::seat.yes') }}
                        </option>
                        <option value="no" @if (setting('allow_tracking', true) == 'no') selected @endif>
                            {{ trans('web::seat.no') }}
                        </option>
                    </select>
                    <p class="form-text text-muted mb-0">
                        {{ trans('web::seat.tracking_help') }}
                        <a href="https://eveseat.github.io/docs/admin_guides/understanding_tracking/">Usage
                            Tracking</a>
                    </p>
                </div>
            </div>

            <!-- Text input-->
            <div class="form-group row">
                <label class="col-md-4 col-form-label" for="tracking_id">{{ trans('web::seat.tracking_id') }}</label>
                <div class="col-md-6">
                    <input id="tracking_id" name="tracking_id" type="text" class="form-control input-md"
                        value="{{ setting('analytics_id', true) }}" disabled>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex">
            <button id="submit" type="submit" class="btn btn-success ms-auto">
                <i class="fas fa-check"></i>
                {{ trans('web::seat.update') }}
            </button>
            </div>
        </div>
    </form>
</div>
