@extends('web::layouts.grids.6-6')

@section('title', trans('web::seat.user_profile'))
@section('page_header', trans('web::seat.user_profile'))

@section('left')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.user_preferences') }}</h3>
    </div>
    <div class="card-body">

      <form role="form" action="{{ route('profile.update.settings') }}" method="post"
            class="form-horizontal" id="user-settings-form">
        {{ csrf_field() }}

          <legend>{{ trans('web::seat.user_interface') }}</legend>

          <!-- Select Basic -->
          <div class="form-group row">
            <label class="col-md-4 col-form-label" for="skin">{{ trans('web::seat.seat_skin') }}</label>
            <div class="col-md-6">
              <select id="skin" name="skin" class="form-control w-100">
                @foreach($skins as $skin)
                  @if(setting('skin') == $skin)
                    <option value="{{ $skin }}" selected>{{ ucwords($skin) }}</option>
                  @else
                    <option value="{{ $skin }}">{{ ucwords($skin) }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>

          <!-- Select Basic -->
          <div class="form-group row">
            <label class="col-md-4 col-form-label" for="language">{{ trans('web::seat.language') }}</label>
            <div class="col-md-6">
              <select id="language" name="language" class="form-control w-100">
                @foreach($languages as $language)
                  <option value="{{ $language['short'] }}"
                          @if(setting('language') == $language['short'])
                          selected
                      @endif>
                    {{ $language['full'] }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Select Basic -->
          <div class="form-group row">
            <label class="col-md-4 col-form-label" for="sidebar">{{ trans('web::seat.sidebar_size') }}</label>
            <div class="col-md-6">
              <select id="sidebar" name="sidebar" class="form-control w-100">
                @foreach($sidebar as $style)
                  <option value="{{ $style }}"
                          @if(setting('sidebar') == $style)
                          selected
                      @endif>
                    {{ str_replace('sidebar-', '', $style) }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Select Basic -->
          <div class="form-group row">
            <label class="col-md-4 col-form-label" for="sidebar">{{ trans('web::seat.mail_as_threads') }}</label>
            <div class="col-md-6">
              <select id="sidebar" name="mail_threads" class="form-control w-100">
                <option value="yes"
                        @if(setting('mail_threads') == "yes") selected @endif>
                  {{ trans('web::seat.yes') }}
                </option>
                <option value="no"
                        @if(setting('mail_threads') == "no") selected @endif>
                  {{ trans('web::seat.no') }}
                </option>
              </select>
            </div>
          </div>

          <legend>{{ trans('web::seat.number_format') }}</legend>

          <!-- Select Basic -->
          <div class="form-group row">
            <label class="col-md-4 col-form-label"
                   for="thousand_seperator">{{ trans('web::seat.thousands_seperator') }}</label>
            <div class="col-md-6">
              <div class="form-inline input-group">
                <select id="thousand_seperator" name="thousand_seperator" class="form-control w-100">
                  @foreach($thousand as $seperator)
                    <option value="{{ $seperator }}" @if(setting('thousand_seperator') == $seperator) selected @endif>
                      @if($seperator == ' ')
                        (space)
                      @else
                        {{ $seperator }}
                      @endif
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <!-- Select Basic -->
          <div class="form-group row">
            <label class="col-md-4 col-form-label"
                   for="decimal_seperator">{{ trans('web::seat.decimal_seperator') }}</label>
            <div class="col-md-6">
              <div class="form-inline input-group">
                <select id="decimal_seperator" name="decimal_seperator" class="form-control w-100">
                  @foreach($decimal as $seperator)
                    <option value="{{ $seperator }}" @if(setting('decimal_seperator') == $seperator) selected @endif>
                      {{ $seperator }}
                    </option>
                  @endforeach
                </select>
              </div>
              <p class="form-text text-muted mb-0">
                {{ trans('web::seat.current_format') }}: {{ number_format(10000000.00, 2, setting('decimal_seperator'), setting('thousand_seperator')) }}
              </p>
            </div>
          </div>

        <div class="form-group row">
          <label class="col-md-4 col-form-label"
                 for="reprocessing_yield">{{ trans('web::seat.reprocessing_yield') }}</label>
          <div class="col-md-6">
            <div class="form-inline input-group">
              <input type="number" min="0" max="1" step="0.0001" name="reprocessing_yield" value="{{ setting('reprocessing_yield') ?: 0.80 }}" class="form-control" id="reprocessing_yield" />
            </div>
          </div>
        </div>

          <legend>{{ trans('web::seat.notifications') }}</legend>

          <!-- Select Basic -->
          <div class="form-group row">
            <label class="col-md-4 col-form-label"
                   for="email_notifications">{{ trans('web::seat.email_notifications') }}</label>
            <div class="col-md-6">
              <div class="form-inline input-group">
                <select id="email_notifications" name="email_notifications" class="form-control w-100">
                  <option value="yes"
                          @if(setting('email_notifications') == "yes") selected @endif>
                    {{ trans('web::seat.yes') }}
                  </option>
                  <option value="no"
                          @if(setting('email_notifications') == "no") selected @endif>
                    {{ trans('web::seat.no') }}
                  </option>
                </select>
              </div>
            </div>
          </div>
      </form>
    </div>


    <div class="card-footer">
      <button id="submit" type="submit" form="user-settings-form" class="btn btn-primary float-right">
        {{ trans('web::seat.update') }}
      </button>
    </div>

  </div>



@stop

@section('right')

  <div class="alert alert-info">{{ trans('web::seat.account_help') }}</div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.user_account') }}</h3>
    </div>
    <div class="card-body">

      <div class="row">
        <div class="col-md-6">

          <ul class="list-unstyled">
            <li class="list-header">{{ trans('web::seat.account_settings') }}</li>
            <li>
              @include('web::profile.buttons.email')
            </li>
            <li>
              @include('web::profile.buttons.login_history')
            </li>
          </ul>

        </div>
        <div class="col-md-6">

          <ul class="list-unstyled">
            <li class="list-header">{{ trans_choice('web::seat.role', 2) }}</li>
            @foreach($user->roles as $role)
              <li>
                <i class="fa fa-group"></i>
                <span @if($role->title == 'Superuser') class="text-danger" @endif>
                  {{ $role->title }}
                </span>
              </li>
            @endforeach
          </ul>

        </div>
      </div>

    </div>
    <div class="card-footer">
      <span class="text-muted float-right">
        <b>{{ trans('web::seat.last_login') }}:</b> {{ auth()->user()->last_login }}
          ({{ human_diff(auth()->user()->last_login) }})
        </span>
    </div>
  </div>

  <div class="callout callout-warning">
    <h4>Third Party Applications</h4>
    <p>{{ trans('web::seat.third_party_access') }}</p>
    <hr/>
    <p class="mb-0 text-right">
      <a class="btn btn-sm btn-warning" style="color: inherit; text-decoration: inherit;" href="https://community.eveonline.com/support/third-party-applications/" target="_blank">{{ trans('web::seat.view_third_party_access') }}</a>
    </p>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.user_sharelink') }}</h3>
    </div>
    <div class="card-body">
      <p>{{ trans('web::seat.user_sharelink_description') }}</p>
      <form method="post" action="{{ route('profile.update.sharelink') }}" class="form-horizontal">
        {{ csrf_field() }}
        <div class="form-group row align-items-center">
          <div class="col-md-7">
            <label class="sr-only" for="user_sharelink_character_id">{{ trans_choice('web::seat.character', 1) }}</label>
            <select class="form-control" id="user_sharelink_character_id" name="user_sharelink_character_id">
              <option value="0" selected>All Characters</option>
              @foreach($characters as $character)
              <option value="{{ $character->character_id }}">{{ $character->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-5">
            <div class="input-group">
              <label class="sr-only" for="user_sharelink_expiry">{{ trans('web::seat.expiry') }}</label>
              <input type="number" min="1" class="form-control" id="user_sharelink_expiry" name="user_sharelink_expiry" placeholder="Expiry (days)">
              <span class="input-group-append">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-plus"></i> {{ trans('web::seat.user_sharelink_generate') }}
                </button>
              </span>
            </div>
          </div>
        </div>
      </form>
      @if(auth()->user()->sharelinks->isNotEmpty())
      <h5 class="mt-2">Existing Links</h5>
      @foreach(auth()->user()->sharelinks as $sharelink)
        <div class="form-group row">
          @if($sharelink->character_id === 0)
            <label class="col-md-2 col-form-label">All Characters</label>
          @else
            <label class="col-md-2 col-form-label">{{ $sharelink->character->name }}</label>
          @endif
          <div class="col-md-5">
            <input type="text" readonly="readonly" class="form-control" value="{{ route('auth.activate.sharelink', ['token' => $sharelink->token]) }}" />
          </div>
          <div class="col-md-2 text-center h-100 my-auto">
            <div class="align-middle">
              @if(carbon()->now()->gt($sharelink->expires_on))
                <span class="text-danger">Expired</span>
              @else
                @include('web::partials.date', ['datetime' => $sharelink->expires_on])
              @endif
            </div>
          </div>
          <div class="col-3">
            <div class="btn-group btn-group-sm h-100 float-right" role="group">
              <button type="button" class="btn btn-primary copy-sharelink">
                <span>
                  <i class="fas fa-link"></i> Copy
                </span>
                <span class="d-none">Copied !</span>
              </button>
              <button type="submit" form="form-sharelink-delete-{{ $loop->iteration }}" class="btn btn-danger">
                <i class="fas fa-trash-alt"></i> Remove
              </button>
            </div>
          </div>
          <form method="post" action="{{ route('profile.update.sharelink.remove') }}" id="form-sharelink-delete-{{ $loop->iteration }}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <input type="hidden" name="token" value="{{ $sharelink->token }}" />
          </form>
        </div>
      @endforeach
      @endif
    </div>
  </div>

  @if(auth()->user()->name != 'admin')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.linked_characters') }}</h3>
      <div class="card-tools">
        <div class="input-group input-group-sm">
          <a href="{{ route('auth.eve') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-link"></i>
            {{ trans('web::seat.link_another_character') }}
          </a>
        </div>
      </div>
    </div>
    <div class="card-body pt-0 pb-0">

      <ul class="list-group list-group-flush">

        @foreach($characters as $character)
          <li class="list-group-item">

            @if ($character->refresh_token)
              <button data-toggle="tooltip" title="Valid Token" class="btn btn-sm btn-link">
                <i class="fa fa-check text-success"></i>
              </button>
            @else
              <button data-toggle="tooltip" title="Invalid Token" class="btn btn-sm btn-link">
                <i class="fa fa-exclamation-triangle text-danger"></i>
              </button>
            @endif

            @if($character->refresh_token)
              @include('web::profile.buttons.scopes')
            @else
              @include('web::profile.buttons.noscopes')
            @endif

            @if($character->refresh_token && setting('allow_user_character_unlink', true) == 'yes')
              @include('web::profile.buttons.unlink')
            @endif

            @include('web::partials.character', ['character' => $character])

          </li>
        @endforeach

      </ul>

    </div>
  </div>
  @endif

  @include('web::profile.modals.email.email')
  @include('web::profile.modals.login_history.login_history')
  @include('web::profile.modals.scopes.scopes')
@stop

@push('javascript')

  <script>

    $("select#main_character_id").select2();
    $("select#user_sharelink_character_id").select2();

    $('#scopesModal').on('show.bs.modal', function (e) {
        var body = $(e.target).find('.modal-body');
        body.html('Loading...');

        $.ajax($(e.relatedTarget).data('url'))
            .done(function (data) {
                body.html(data);
            });
    });

    $(document).on('click', '.copy-sharelink', function (e) {
        var button = $(this);
        var input = button.closest('.form-group').find('input');

        input.select();
        document.execCommand('copy');

        button.find('span').toggleClass('d-none');
        setTimeout(function() { button.find('span').toggleClass('d-none'); }, 500);
    });

  </script>

@endpush
