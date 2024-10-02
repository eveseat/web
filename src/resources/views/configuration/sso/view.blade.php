@extends('web::layouts.grids.6-6')

@section('title', trans('web::seat.sso'))
@section('page_header', trans('web::seat.sso'))

@section('left')


  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">
        {{ trans('web::seat.profile') }}
      </h3>
    </div>

    <div class="card-body">
      <form role="form" id="profile" action="{{ route('seatcore::configuration.sso') }}" method="post">
        {{ csrf_field() }}
        <div class="form-group row">
          <label for="available_profiles">{{ trans('web::seat.profile') }}</label>
          <select name="profile" id="available_profiles" class="custom-select">
            @foreach(setting('sso_scopes', true) as $scope)
            <option value="{{ $scope->id }}" {{ ($scope->id == $selected_profile->id) ? 'selected' : ''}}>{{ $scope->name }}</option>
            @endforeach
          </select>
        </div>
      </form>
      <a href="{{ route('seatcore::configuration.sso.add_profile') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add new Profile</a>
      <a href="{{ route('seatcore::configuration.sso.delete_profile', $selected_profile->id) }}" class="btn btn-danger"><i class="fas fa-trash"></i> Delete Profile</a>
    </div>
  </div>
  
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">
        {{ trans('web::seat.sso_scopes') }}
      </h3>
      <div class="card-tools">
        <div class="input-group input-group-sm btn-group btn-group-sm">
          <a href="{{ route('seatcore::configuration.sso.set_default_profile', $selected_profile->id) }}" id="set_default" class="btn btn-sm btn-info">
            {{ trans('web::seat.set_default') }}
          </a>
          <button role="button" id="enable_all" class="btn btn-sm btn-primary">
            {{ trans('web::seat.enable_all') }}
          </button>
          <button role="button" id="remove_all" class="btn btn-sm btn-danger">
            {{ trans('web::seat.remove_all') }}
          </button>
        </div>
      </div>
    </div>

    <div class="card-body">

      <form role="form" action="{{ route('seatcore::configuration.sso.update_scopes') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="profile_id" id="profile_id" value="{{ $selected_profile->id }}" />

        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="profile_name">{{ trans('web::seat.sso_scopes_profile_name') }}</label>
              <input type="text" name="profile_name" id="profile_name" class="form-control" value="{{ $selected_profile->name }}" />
              <small class="form-text text-muted">{{ trans('web::seat.sso_scopes_profile_name_help') }}</small>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="available_scopes">{{ trans('web::seat.available_scopes') }}</label>
              <select name="scopes[]" id="available_scopes" class="form-control select2" style="width: 100%;"
                      multiple="multiple" data-placeholder="{{ trans('web::seat.select_item_add') }}">

                @foreach(config('eveapi.scopes') as $scope)

                  <option value="{{ $scope }}" @if (! is_null($selected_profile->scopes) && in_array($scope, $selected_profile->scopes)) selected @endif>
                    {{ $scope }}
                  </option>

                @endforeach

              </select>

            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-success btn-block">
              {{ trans('web::seat.update_sso_scopes') }}
            </button>
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
        {{ trans('web::seat.current_configuration_status') }}
      </h3>
    </div>

    <div class="card-body">

      <dl>
        <dt>{{ trans('web::seat.client_id_status') }}</dt>
        <dd>
          @if(strlen(config('eseye.esi.auth.client_id')) > 5)
            <p class="text-green">{{ trans('web::seat.client_id_ok') }}</p>
          @else
            <p class="text-red">{{ trans('web::seat.client_id_not_ok') }}</p>
          @endif
        </dd>

        <dt>{{ trans('web::seat.client_secret_status') }}</dt>
        <dd>
          @if(strlen(config('eseye.esi.auth.client_secret')) > 5)
            <p class="text-green">{{ trans('web::seat.client_secret_ok') }}</p>
          @else
            <p class="text-red">{{ trans('web::seat.client_secret_not_ok') }}</p>
          @endif
        </dd>

        <dt>{{ trans('web::seat.callback_url_status') }}</dt>
        <dd>
          @if(config('eseye.esi.auth.client_callback') == route('seatcore::auth.eve.callback'))
            <p class="text-green">{{ trans('web::seat.callback_url_ok', ['url' => config('eseye.esi.auth.client_callback')]) }}</p>
          @elseif(strlen(config('eseye.esi.auth.client_callback')) > 5)
            <p class="text-yellow">
              {{ trans('web::seat.callback_maybe_wrong', ['current' => config('eseye.esi.auth.client_callback'), 'suggested' => route('seatcore::auth.eve.callback')]) }}
            </p>
          @else
            <p class="text-red">{{ trans('web::seat.client_callback_not_ok', ['url' => route('seatcore::auth.eve.callback')]) }}</p>
          @endif
        </dd>

      </dl>

    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        {{ trans('web::seat.custom_signin_page') }}
      </h3>
    </div>

    <div class="card-body">
      <p>{{ trans('web::seat.custom_signin_page_desc') }}</p>
      <form method="post" action="{{ route('seatcore::configuration.sso.update_custom_signin') }}" enctype="multipart/form-data" id="customise-login-form">
        {{ csrf_field() }}
        <input type="hidden" name="message" value="" />
        <div id="login_page_content"></div>
        <div class="float-right mt-2">
            <button type="submit" form="customise-login-form" class="btn btn-success">
              <i class="fas fa-save"></i> Save
            </button>
          </div>
      </form>
    </div>
  </div>

@stop

@push('head')
  <link href="{{ asset('web/css/quill.snow.css') }}" rel="stylesheet" />
@endpush

@push('javascript')

<script src="{{ asset('web/js/quill.min.js') }}"></script>

  <script>
    Quill.prototype.getHtml = function () {
        var html = this.container.querySelector('.ql-editor').innerHTML;
        html = html.replace(/<p>(<br>|<br\/>|<br\s\/>|\s+|)<\/p>/gmi, "");
        return html;
    };

    var editor = new Quill('#login_page_content', {
        modules: {
            toolbar: {
                container: 
                [
                  [{'header': ['1', '2', '3', '4', '5', '6', false]}, {'color': []}],
                  ['bold', 'italic', 'underline', 'strike'],
                  [{'list': 'ordered'}, {'list': 'bullet'}],
                  [{'align': []}, {'indent': '-1'}, {'indent': '+1'}],
                  ['link'],
                  ['image'],
                  ['clean'],
                  [{ 'placeholder': [
                    @foreach(setting('sso_scopes', true) as $scope)
                      '[[{{ $scope->name }}]]'{{ $loop->last ? '' : ',' }} 
                    @endforeach
                  ] }]
                ],
                handlers: {
                  "placeholder": function (value) { 
                    if (value) {
                      const cursorPosition = this.quill.getSelection().index;
                      this.quill.insertText(cursorPosition, value);
                      this.quill.setSelection(cursorPosition + value.length);
                    }
                  }
                }
            }
        },
        placeholder: 'Compose login page...',
        theme: 'snow'
    });

    editor.setContents(editor.clipboard.convert('{!! addslashes($custom_signin_message) !!}'), 'silent');

    // We need to manually supply the HTML content of our custom dropdown list
    const placeholderPickerItems = Array.prototype.slice.call(document.querySelectorAll('.ql-placeholder .ql-picker-item'));
    placeholderPickerItems.forEach(item => item.textContent = item.dataset.value);
    document.querySelector('.ql-placeholder .ql-picker-label').innerHTML
        = 'Insert login button' + document.querySelector('.ql-placeholder .ql-picker-label').innerHTML;

    $('#customise-login-form').on('submit', function () {
        $('input[name="message"]').val(editor.getHtml());
    });
  </script>

  @include('web::includes.javascript.id-to-name')

  <script>
    $("#available_scopes").select2();

    $("#available_profiles").change(function () {
      $("#profile").trigger("submit");
    });

    $("#enable_all").click(function() {
      $("#available_scopes > option").prop("selected","selected");
      $("#available_scopes").trigger("change");
    });

    $("#remove_all").click(function() {
      $("#available_scopes > option").removeAttr("selected");
      $("#available_scopes").trigger("change");
    });
  </script>

@endpush