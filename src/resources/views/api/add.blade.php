@extends('web::layouts.grids.4-8')

@section('title', trans('web::seat.api_key_add'))
@section('page_header', trans('web::seat.api_key_add'))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.api_key_add') }}</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="#" method="post" id="key-form">
        {{ csrf_field() }}

        <div class="box-body">

          <div class="form-group">
            <label for="key_id">{{ trans('web::seat.key_id') }}</label>
            <input type="text" name="key_id" class="form-control" id="key_id" value="{{ old('key_id') }}"
                   placeholder="{{ trans('web::seat.key_id') }}">
          </div>

          <div class="form-group">
            <label for="text">{{ trans('web::seat.v_code') }}</label>
            <input type="text" name="v_code" class="form-control" id="v_code" value=""
                   placeholder="{{ trans('web::seat.v_code') }}">
          </div>

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-primary pull-right">
            {{ trans('web::seat.check_key') }}
          </button>
        </div>
      </form>

    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.api_new_key') }}</h3>
    </div>
    <div class="panel-body">

      <p>
      <span class="text-muted">
        {{ trans('web::seat.api_use_link') }}
      </span>
      </p>

      <ul>
        @if(setting('force_min_mask', true) == 'yes')

          <li>
            <a href="https://community.eveonline.com/support/api-key/CreatePredefined?accessMask={{ setting('min_character_access_mask', true) }}"
               target="_blank">
              {{ trans('web::seat.api_full_min_mask') }}
            </a>
          </li>

        @else

          <li>
            <a href="https://community.eveonline.com/support/api-key/CreatePredefined?accessMask={{ config('web.config.max_access_mask') }}"
               target="_blank">
              {{ trans('web::seat.api_full_link') }}
            </a>
          </li>

        @endif
      </ul>

    </div>
  </div>

@stop

@section('right')

  <span id="result"></span>

@stop

@push('javascript')
  <script type="text/javascript">

    // variable to hold request
    var request;
    // bind to the submit event of our form
    $("#key-form").submit(function (event) {

      // abort any pending request
      if (request) {
        request.abort();
      }
      // setup some local variables
      var $form = $(this);
      // let's select and cache all the fields
      var $inputs = $form.find("input, select, button, textarea");
      // serialize the data in the form
      var serializedData = $form.serialize();

      // let's disable the inputs for the duration of the ajax request
      // Note: we disable elements AFTER the form data has been serialized.
      // Disabled form elements will not be serialized.
      $inputs.prop("disabled", true);

      // Show the results box and a loader
      $("span#result").html("<i class='fa fa-cog fa-spin'></i> Loading...");

      // fire off the request to /form.php
      request = $.ajax({
        url : "{{ route('api.key.check') }}",
        type: "post",
        data: serializedData
      });

      // callback handler that will be called on success
      request.done(function (response, textStatus, jqXHR) {
        $("span#result").html(response);
      });

      // callback handler that will be called on failure
      request.fail(function (jqXHR, textStatus, errorThrown) {

        // Validation errors response with HTTP 422
        if (jqXHR.status === 422) {

          //process validation errors here.
          var errors = jqXHR.responseJSON; //this will get the errors response data.
          var errorsHtml = '<div class="alert alert-danger"><ul>';

          $.each(errors, function (key, value) {
            errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
          });
          errorsHtml += '</ul></di>';

          $("span#result").html(errorsHtml);

        } else {

          // Log the errors to the console
          console.log(textStatus);
        }

      });

      // callback handler that will be called regardless
      // if the request failed or succeeded
      request.always(function () {
        // reenable the inputs
        $inputs.prop("disabled", false);
      });

      // prevent default posting of form
      event.preventDefault();
    });

  </script>
@endpush
