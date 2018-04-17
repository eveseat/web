@extends('web::layouts.grids.6-6')

@section('title', trans('web::seat.user_profile'))
@section('page_header', trans('web::seat.user_profile'))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.user_preferences') }}</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('profile.update.settings') }}" method="post"
            class="form-horizontal">
        {{ csrf_field() }}

        <div class="box-body">

          <legend>{{ trans('web::seat.user_interface') }}</legend>

          <!-- Select Basic -->
          <div class="form-group">
            <label class="col-md-4 control-label"
                   for="main_character_id">{{ trans('web::seat.main_character') }}</label>
            <div class="col-md-6">
              <select id="main_character_id" name="main_character_id" class="form-control">
                @foreach($characters as $character)
                  <option value="{{ $character->id }}"
                          @if(setting('main_character_id') == $character->id)
                          selected
                      @endif>
                    {{ $character->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Select Basic -->
          <div class="form-group">
            <label class="col-md-4 control-label" for="skin">{{ trans('web::seat.seat_skin') }}</label>
            <div class="col-md-6">
              <select id="skin" name="skin" class="form-control">
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
          <div class="form-group">
            <label class="col-md-4 control-label" for="language">{{ trans('web::seat.language') }}</label>
            <div class="col-md-6">
              <select id="language" name="language" class="form-control">
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
          <div class="form-group">
            <label class="col-md-4 control-label" for="sidebar">{{ trans('web::seat.sidebar_size') }}</label>
            <div class="col-md-6">
              <select id="sidebar" name="sidebar" class="form-control">
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
          <div class="form-group">
            <label class="col-md-4 control-label" for="sidebar">{{ trans('web::seat.mail_as_threads') }}</label>
            <div class="col-md-6">
              <select id="sidebar" name="mail_threads" class="form-control">
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
          <div class="form-group">
            <label class="col-md-4 control-label"
                   for="thousand_seperator">{{ trans('web::seat.thousands_seperator') }}</label>
            <div class="col-md-6">
              <div class="form-inline input-group">
                <select id="thousand_seperator" name="thousand_seperator" class="form-control">
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
          <div class="form-group">
            <label class="col-md-4 control-label"
                   for="decimal_seperator">{{ trans('web::seat.decimal_seperator') }}</label>
            <div class="col-md-6">
              <div class="form-inline input-group">
                <select id="decimal_seperator" name="decimal_seperator" class="form-control">
                  @foreach($decimal as $seperator)
                    <option value="{{ $seperator }}" @if(setting('decimal_seperator') == $seperator) selected @endif>
                      {{ $seperator }}
                    </option>
                  @endforeach
                </select>
              </div>
              <span class="help-block">
                {{ trans('web::seat.current_format') }}: {{ number(10000000.00) }}
              </span>
            </div>
          </div>

          <legend>{{ trans('web::seat.notifications') }}</legend>

          <!-- Select Basic -->
          <div class="form-group">
            <label class="col-md-4 control-label"
                   for="email_notifications">{{ trans('web::seat.email_notifications') }}</label>
            <div class="col-md-6">
              <div class="form-inline input-group">
                <select id="email_notifications" name="email_notifications" class="form-control">
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
          <div class="form-group">
            <label class="col-md-4 control-label" for="submit"></label>
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

  <div class="panel panel-default">
    <div class="panel-body">
      {{ trans('web::seat.third_party_access') }}
      <a href="https://community.eveonline.com/support/third-party-applications/" target="_blank">Third Party
        Applications</a>
    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans('web::seat.user_account') }}
        <span class="pull-right">
          {{ trans('web::seat.last_login') }}: {{ auth()->user()->last_login }}
          ({{ human_diff(auth()->user()->last_login) }})
        </span>
      </h3>
    </div>
    <div class="panel-body">

      <div class="row">
        <div class="col-md-6">

          <ul class="list-unstyled">
            <li class="list-header">{{ trans('web::seat.account_settings') }}</li>
            <li>

              <!-- Button trigger modal -->
              <a type="button" data-toggle="modal" data-target="#emailModal">
                <i class="fa fa-envelope"></i>
                {{ trans('web::seat.change_email') }}
              </a>

              <!-- Modal -->
              <div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                      <h4 class="modal-title" id="emailModalLabel">{{ trans('web::seat.change_email') }}</h4>
                    </div>
                    <div class="modal-body">

                      <form role="form" action="{{ route('profile.update.email') }}" method="post">
                        {{ csrf_field() }}

                        <div class="box-body">

                          <div class="form-group">
                            <label for="current_email">{{ trans('web::seat.current_email') }}</label>
                            <input type="email" name="current_email" class="form-control" placeholder="Current Email"
                                   value="{{ auth()->user()->email }}" disabled="disabled"/>
                          </div>

                          <div class="form-group">
                            <label for="new_email">{{ trans('web::seat.new_email') }}</label>
                            <input type="email" name="new_email" class="form-control" placeholder="New Email"
                                   required="required" />
                          </div>

                          <div class="form-group">
                            <label for="new_email_confirmation">{{ trans('web::seat.confirm_new_email') }}</label>
                            <input type="email" name="new_email_confirmation" class="form-control"
                                   id="email_confirmation" placeholder="New Email Confirmation" required="required" />
                          </div>

                        </div><!-- /.box-body -->

                        <div class="box-footer">
                          <button type="submit" class="btn btn-primary pull-right">
                            {{ trans('web::seat.change_email') }}
                          </button>
                        </div>
                      </form>

                    </div>
                  </div>
                </div>
              </div>

            </li>
            <li>

              <!-- Button trigger modal -->
              <a type="button" data-toggle="modal" data-target="#historyModal">
                <i class="fa fa-lock"></i>
                {{ trans('web::seat.login_history') }}
              </a>

              <!-- Modal -->
              <div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                      <h4 class="modal-title" id="historyModalLabel">{{ trans('web::seat.login_history') }}</h4>
                    </div>
                    <div class="modal-body">

                      <table class="table table-condensed table-hover table-responsive">
                        <tbody>
                        <tr>
                          <th>{{ trans('web::seat.date') }}</th>
                          <th>{{ trans('web::seat.action') }}</th>
                          <th>{{ trans('web::seat.source') }}</th>
                          <th>{{ trans('web::seat.user_agent') }}</th>
                        </tr>

                        @foreach($history as $entry)

                          <tr>
                            <td>
                              <span data-toggle="tooltip"
                                    title="" data-original-title="{{ $entry->created_at }}">
                                {{ human_diff($entry->created_at) }}
                              </span>
                            </td>
                            <td>{{ ucfirst($entry->action) }}</td>
                            <td>{{ $entry->source }}</td>
                            <td>{{ str_limit($entry->user_agent) }}</td>
                          </tr>

                        @endforeach

                        </tbody>
                      </table>

                    </div>
                  </div>
                </div>
              </div>

            </li>

            <!-- scopes -->
            <li>

              <!-- Button trigger modal -->
              <a type="button" data-toggle="modal" data-target="#scopesModal">
                <i class="fa fa-shield"></i>
                {{ trans_choice('web::seat.scope', 2) }}
              </a>

              <!-- Modal -->
              <div class="modal fade" id="scopesModal" tabindex="-1" role="dialog" aria-labelledby="scopesModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                      <h4 class="modal-title" id="scopesModalLabel">{{ trans_choice('web::seat.scope', 2) }}</h4>
                    </div>
                    <div class="modal-body">

                      <table class="table table-condensed table-hover table-responsive">
                        <tbody>

                          @unless(is_null($scopes))

                            @foreach($scopes as $scope)

                              <tr>
                                <td>{{ $scope }}</td>
                              </tr>

                            @endforeach

                          @endunless

                        </tbody>
                      </table>

                    </div>
                  </div>
                </div>
              </div>

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

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">

        {{ trans('web::seat.linked_characters') }}

        <span class="pull-right">
          <a href="{{ route('auth.eve') }}" class="btn btn-primary btn-xs">
            {{ trans('web::seat.link_another_character') }}
          </a>
        </span>

      </h3>
    </div>
    <div class="panel-body">

      <div class="row">
        <div class="col-md-12">

          <ul class="list-unstyled">

            @foreach($characters as $character)
              <li>

                {!! img('character', $character->id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                {{ $character->name }}

              </li>
            @endforeach

          </ul>

        </div>
      </div>

    </div>
  </div>

  <span class="text-center">
    {{ trans('web::seat.account_help') }}
  </span>

@stop

@push('javascript')

  <script>

    $("select#main_character_id").select2();

  </script>

@endpush
