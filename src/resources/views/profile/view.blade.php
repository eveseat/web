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
            class="form-horizontal">
        {{ csrf_field() }}

        <div class="box-body">

          <legend>{{ trans('web::seat.user_interface') }}</legend>

          @if(auth()->user()->name != 'admin')
          <!-- Select Basic -->
          <div class="form-group row">
            <label class="col-md-4 col-form-label"
                   for="main_character_id">{{ trans('web::seat.main_character') }}</label>
            <div class="col-md-6">
              <select id="main_character_id" name="main_character_id" class="form-control" style="width: 100%;">
                @foreach($characters as $character)
                  <option value="{{ $character->character_id }}"
                          @if(setting('main_character_id') == $character->id)
                          selected
                      @endif>
                    {{ $character->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          @endif

          <!-- Select Basic -->
          <div class="form-group row">
            <label class="col-md-4 col-form-label" for="skin">{{ trans('web::seat.seat_skin') }}</label>
            <div class="col-md-6">
              <select id="skin" name="skin" class="form-control" style="width: 100%;">
                @foreach($skins as $skin)
                  <option value="{{ $skin }}"
                          @if(setting('skin') == $skin)
                          selected
                      @endif>
                    {{ str_replace('skin-', '', $skin) }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Select Basic -->
          <div class="form-group row">
            <label class="col-md-4 col-form-label" for="language">{{ trans('web::seat.language') }}</label>
            <div class="col-md-6">
              <select id="language" name="language" class="form-control" style="width: 100%;">
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
              <select id="sidebar" name="sidebar" class="form-control" style="width: 100%;">
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
              <select id="sidebar" name="mail_threads" class="form-control" style="width: 100%;">
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
                <select id="thousand_seperator" name="thousand_seperator" class="form-control" style="width: 100%;">
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
                <select id="decimal_seperator" name="decimal_seperator" class="form-control" style="width: 100%;">
                  @foreach($decimal as $seperator)
                    <option value="{{ $seperator }}" @if(setting('decimal_seperator') == $seperator) selected @endif>
                      {{ $seperator }}
                    </option>
                  @endforeach
                </select>
              </div>
              <p class="form-text text-muted mb-0">
                {{ trans('web::seat.current_format') }}: {{ number_format(10000000.00) }}
              </p>
            </div>
          </div>

          <legend>{{ trans('web::seat.notifications') }}</legend>

          <!-- Select Basic -->
          <div class="form-group row">
            <label class="col-md-4 col-form-label"
                   for="email_notifications">{{ trans('web::seat.email_notifications') }}</label>
            <div class="col-md-6">
              <div class="form-inline input-group">
                <select id="email_notifications" name="email_notifications" class="form-control" style="width: 100%;">
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

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <div class="form-group row">
            <label class="col-md-4 col-form-label" for="submit"></label>
            <div class="col-md-4">
              <button id="submit" type="submit" class="btn btn-primary">
                {{ trans('web::seat.update') }}
              </button>
            </div>
          </div>
        </div>
      </form>

    </div>
  </div>



@stop

@section('right')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        {{ trans('web::seat.user_account') }}
        <span class="float-right">
          {{ trans('web::seat.last_login') }}: {{ auth()->user()->last_login }}
          ({{ human_diff(auth()->user()->last_login) }})
        </span>
      </h3>
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
  </div>

  <div class="card">
    <div class="card-body">
      <p>{{ trans('web::seat.third_party_access') }}</p>
      <a href="https://community.eveonline.com/support/third-party-applications/" target="_blank"
         class="btn btn-success btn-sm float-right">
        {{ trans('web::seat.view_third_party_access') }}
      </a>
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
            @endif

            @include('web::partials.character', ['character' => $character])

          </li>
        @endforeach

      </ul>

    </div>
  </div>
  @endif

  <span class="text-center">
    {{ trans('web::seat.account_help') }}
  </span>

  @include('web::profile.modals.email.email')
  @include('web::profile.modals.login_history.login_history')
  @include('web::profile.modals.scopes.scopes')
@stop

@push('javascript')

  <script>

    $("select#main_character_id").select2();

    $('#scopesModal').on('show.bs.modal', function (e) {
        var body = $(e.target).find('.modal-body');
        body.html('Loading...');

        $.ajax($(e.relatedTarget).data('url'))
            .done(function (data) {
                body.html(data);
            });
    });

  </script>

@endpush
