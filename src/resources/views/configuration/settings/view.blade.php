@extends('web::layouts.grids.6-6')

@section('title', trans('web::seat.settings'))
@section('page_header', trans('web::seat.settings'))

@section('left')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.settings') }}</h3>
    </div>
    <div class="card-body">

      <form role="form" action="{{ route('seatcore::seat.update.settings') }}" method="post"
            class="form-horizontal">
        {{ csrf_field() }}

        <div class="box-body">

          <legend>{{ trans('web::seat.admin_email') }}</legend>

          <!-- Text input-->
          <div class="form-group row">
            <label class="col-md-4 col-form-label" for="admin_contact">{{ trans('web::seat.admin_email') }}</label>
            <div class="col-md-6">
              <input id="admin_contact" name="admin_contact" type="email"
                     class="form-control input-md" value="{{ setting('admin_contact', true) }}">
              <p class="form-text text-muted mb-0">
                {{ trans('web::seat.admin_email_help') }}
              </p>
            </div>
          </div>

          <legend>{{ trans_choice('web::settings.jobs', 0) }}</legend>

          <!-- Text input -->
          <div class="form-group row">
            <label class="col-md-4 col-form-label" for="market_prices_region">{{ trans('web::settings.market_prices_region') }}</label>
            <div class="col-md-6">
              <select id="market_prices_region" name="market_prices_region" class="form-control" style="width: 100%;"></select>
              <p class="form-text text-muted mb-0">{{ trans('web::settings.market_prices_region_help') }}</p>
            </div>
          </div>

          <legend>{{ trans('web::seat.maintenance') }}</legend>

          <!-- Text input-->
          <div class="form-group row">
            <label class="col-md-4 col-form-label" for="cleanup">{{ trans('web::seat.cleanup_data') }}</label>
            <div class="col-md-6">
              <select id="cleanup" name="cleanup_data" class="form-control" style="width: 100%;">
                <option value="yes"
                        @if(setting('cleanup_data', true) == "yes") selected @endif>
                  {{ trans('web::seat.yes') }}
                </option>
                <option value="no"
                        @if(setting('cleanup_data', true) == "no") selected @endif>
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
            <label class="col-md-4 col-form-label" for="registration">{{ trans('web::seat.allow_registration') }}</label>
            <div class="col-md-6">
              <select id="registration" name="registration" class="form-control" style="width: 100%;">
                <option value="yes"
                        @if(setting('registration', true) == "yes") selected @endif>
                  {{ trans('web::seat.yes') }}
                </option>
                <option value="no"
                        @if(setting('registration', true) == "no") selected @endif>
                  {{ trans('web::seat.no') }}
                </option>
              </select>
            </div>
          </div>

          <!-- User Character Unlink -->
          <div class="form-group row">
            <label class="col-md-4 col-form-label" for="allow_user_character_unlink">{{ trans('web::seat.allow_user_character_unlink') }}</label>
            <div class="col-md-6">
              <select id="allow_user_character_unlink" name="allow_user_character_unlink" class="form-control" style="width: 100%;">
                <option value="yes"
                        @if(setting('allow_user_character_unlink', true) == "yes") selected @endif>
                  {{ trans('web::seat.yes') }}
                </option>
                <option value="no"
                        @if(in_array(setting('allow_user_character_unlink', true), [null, 'no'])) selected @endif>
                  {{ trans('web::seat.no') }}
                </option>
              </select>
            </div>
          </div>

          <legend>{{ trans('web::seat.google_analytics') }}</legend>

          <!-- Select Basic -->
          <div class="form-group row">
            <label class="col-md-4 col-form-label" for="allow_tracking">{{ trans('web::seat.allow_tracking') }}</label>
            <div class="col-md-6">
              <select id="allow_tracking" name="allow_tracking" class="form-control" style="width: 100%;">
                <option value="yes"
                        @if(setting('allow_tracking', true) == "yes") selected @endif>
                  {{ trans('web::seat.yes') }}
                </option>
                <option value="no"
                        @if(setting('allow_tracking', true) == "no") selected @endif>
                  {{ trans('web::seat.no') }}
                </option>
              </select>
              <p class="form-text text-muted mb-0">
                {{ trans('web::seat.tracking_help') }}
                <a href="https://eveseat.github.io/docs/admin_guides/understanding_tracking/">Usage Tracking</a>
              </p>
            </div>
          </div>

          <!-- Text input-->
          <div class="form-group row">
            <label class="col-md-4 col-form-label" for="tracking_id">{{ trans('web::seat.tracking_id') }}</label>
            <div class="col-md-6">
              <input id="tracking_id" name="tracking_id" type="text"
                     class="form-control input-md" value="{{ setting('analytics_id', true) }}" disabled>
            </div>
          </div>

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <div class="form-group row">
            <label class="col-md-4 col-form-label" for="submit"></label>
            <div class="col-md-4">
              <button id="submit" type="submit" class="btn btn-success">
                <i class="fas fa-check"></i>
                {{ trans('web::seat.update') }}
              </button>
            </div>
          </div>
        </div>
      </form>

    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.customlink') }}</h3>
    </div>
    <div class="card-body">
      <p>{!! trans('web::seat.customlink_description', ['fa-link' => '<a href="https://fontawesome.com/icons?d=gallery" target="_blank">Font Awesome</a>', 'icon' => '<code>fas fa-link</code>']) !!}</p>

      <form role="form" action="{{ route('seatcore::seat.update.customlink') }}" method="post">
        {{ csrf_field() }}
        <div class="row">
          <div class="col-md-4">{{ trans_choice('web::seat.name', 1) }}</div>
          <div class="col">{{ strtoupper(trans('web::seat.url')) }}</div>
          <div class="col-md-2">Icon</div>
          <div class="col-md-2">{{ trans('web::seat.new_tab_question_mark') }}</div>
        </div>
        <div id="customlinks">

          @foreach($custom_links as $node)

          <div class="form-row align-items-center my-3" id="customlink{{ $loop->index }}">
            <div class="col-md-4">
              <input type="text" class="form-control" name="customlink-name[]" value="{{ $node->name }}" />
            </div>
            <div class="col">
              <input type="text" class="form-control" name="customlink-url[]" value="{{ $node->url }}" />
            </div>
            <div class="col-md-2">
              <input type="text" class="form-control" name="customlink-icon[]" value="{{ $node->icon }}" />
            </div>
            <div class="col-md-2 form-inline">
              <select class="form-control" name="customlink-newtab[]">
                <option value="1" {{ ($node->new_tab === false) ? '' : 'selected="selected"' }}>Yes</option>
                <option value="0" {{ ($node->new_tab === false) ? 'selected="selected"' : '' }}>No</option>
              </select>
              <button type="button" class="btn btn-danger btn-md ml-2" id="customlink-remove-btn" onclick="customlink_remove(this)">
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          </div>

          @endforeach
        </div>

        <div class="float-right">
          <button type="button" class="btn btn-link" id="customlink-add-btn"><i class="fas fa-plus"></i> Add new link</button>
          <button type="submit" class="btn btn-success">
            <i class="fas fa-check"></i> Update
          </button>
        </div>
      </form>
    </div>
  </div>

@stop

@section('right')
  <div class="card">
    <div class="card-header d-flex p-0">
      <h3 class="card-title p-3">
        <i class="fas fa-code-fork"></i>
        {{ trans('web::seat.module_versions') }}
      </h3>
      <ul class="nav nav-pills ml-auto p-2">
        <li class="nav-item">
          <a href="#core-packages" data-toggle="pill" aria-expanded="true" class="nav-link active">Core</a>
        </li>
        <li class="nav-item">
          <a href="#plugin-packages" data-toggle="pill" aria-expanded="false" class="nav-link">Plugins</a>
        </li>
        <li class="nav-item">
          <a href="#" data-toggle="tooltip" title="Click to copy packages versions" id="copy-versions" class="nav-link">
            <i class="fas fa-copy"></i>
          </a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      <div class="tab-content">
        <div id="core-packages" class="tab-pane active">
          <dl>

            @foreach($packages->core as $package)
              @include('web::configuration.settings.partials.packages.version')
            @endforeach

          </dl>
        </div>
        <div id="plugin-packages" class="tab-pane">
          <dl>

            @foreach($packages->plugins as $package)
              @include('web::configuration.settings.partials.packages.version')
            @endforeach

          </dl>
        </div>
        <div class="row text-center">
          <div class="col-md-4">
            <i class="fas fa-question-circle text-orange"></i> Checking packages (<span id="checking-packages">{{ $packages->core->count() + $packages->plugins->count() }}</span>)
          </div>
          <div class="col-md-4">
            <i class="fas fa-check-circle text-green"></i> Updated packages (<span id="updated-packages">0</span>)
          </div>
          <div class="col-md-4">
            <i class="fas fa-times-circle text-red"></i> Outdated packages (<span id="outdated-packages">0</span>)
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.tp_versions') }}</h3>
    </div>
    <div class="card-body">

      <dl>

        <dt>
          <i id="live-sde-status" 
            class="fas fa-question-circle text-orange version-check"
            data-vendor="ccp"
            data-name="eve_online_sde"
            data-version="{{ setting('installed_sde', true) }}"
            data-toggle="tooltip"
            title="Checking package status..."></i> Eve Online SDE</dt>
        <dd>
          <ul>
            <li>{{ trans('web::seat.installed') }}: <b>{{ setting('installed_sde', true) }}</b></li>
            <li id="live-sde-version">{{ trans('web::seat.current') }}: <img
                      src="https://img.shields.io/badge/version-loading...-blue.svg?style=flat-square"></li>
            <li>{{ trans('web::seat.url') }}: <a href="https://www.fuzzwork.co.uk/dump" target="_blank">https://www.fuzzwork.co.uk/dump</a></li>
          </ul>
        </dd>

      </dl>

    </div>

  </div>
  @include('web::configuration.settings.partials.packages.changelog.modal')

@stop

@push('javascript')
<script type="text/javascript">
  var customlink_template = '<div class="col-md-4">' +
    '<input type="text" class="form-control" name="customlink-name[]" />' +
    '</div>' +
    '<div class="col">' +
    '<input type="text" class="form-control" name="customlink-url[]" />' +
    '</div>' +
    '<div class="col-md-2">' +
    '<input type="text" class="form-control" name="customlink-icon[]" />' +
    '</div>'+
    '<div class="col-md-2 form-inline">' +
    '<select class="form-control" name="customlink-newtab[]">' +
    '<option value="1" selected="selected">Yes</option>' +
    '<option value="0">No</option>' +
    '</select>' +
    '<button type="button" class="btn btn-danger btn-md ml-2" id="customlink-remove-btn" onclick="customlink_remove(this)">' +
    '<i class="fas fa-trash-alt"></i></button>' +
    '</div>';

  $('#customlink-add-btn').on('click', function() {
    var menu_node = document.createElement('div');
    menu_node.className += 'form-row align-items-center my-3';
    menu_node.innerHTML = customlink_template;
    document.getElementById('customlinks').appendChild(menu_node);
  });

  function customlink_remove(ele) {
    $(ele).parent().parent().remove();
  }

  $(document).ready(function () {
    var market_prices_region = $('#market_prices_region');
    market_prices_region.select2({
        ajax: {
            url: '{{ route('seatcore::fastlookup.regions') }}',
            dataType: 'json'
        }
    });

    $.ajax({
        type: 'get',
        url: '{{ route('seatcore::fastlookup.regions') }}?_type=find&q={{ setting('market_prices_region_id', true) ?: 0 }}'
    }).then(function (data) {
        var option = new Option(data.text, data.id, true, true);
        market_prices_region.append(option).trigger('change');

        market_prices_region.trigger({
            type: 'select2:select',
            params: {
                data: data
            }
        });
    });

    var installedPackages = {
      "vendor": ["Vendor"],
      "name": ["Package Name"],
      "version": ["Installed Version"]
    };

    var copyVersions = $('#copy-versions');

    jQuery.get("{{ route('seatcore::check.sde') }}", function (data) {
      var live_sde = "error";
      if (data != null) {
        live_sde = data.version;
      }
      $('#live-sde-version img').attr('src', 'https://img.shields.io/badge/version-' + live_sde.replace(/-/g, '--') + '-blue.svg?style=flat-square');
      if (live_sde != "error") {
        var liveSdeStatus = $('#live-sde-status');
        var liveSdeOutdated = (live_sde != liveSdeStatus.attr('data-version'));

        // remove pending state from the status
        liveSdeStatus.removeClass('fa-question-circle');
        liveSdeStatus.removeClass('text-orange');

        // update state according to result
        liveSdeStatus.addClass(liveSdeOutdated ? 'fa-times-circle' : 'fa-check-circle');
        liveSdeStatus.addClass(liveSdeOutdated ? 'text-red' : 'text-green');
        liveSdeStatus.attr('title', liveSdeOutdated ? 'At least one new version has been released !' : 'The package is up-to-date.');
        liveSdeStatus.attr('data-original-title', liveSdeOutdated ? 'At least one new version has been released !' : 'The package is up-to-date.');
      }
    });

    copyVersions.on('click', function() {
      var buffer = "```\r\n";

      var colVendorSize = getLongestString(installedPackages.vendor);
      var colNameSize = getLongestString(installedPackages.name);
      var colVersionSize = getLongestString(installedPackages.version);

      // loop over the versions object and build a markdown formatted table
      $.each(installedPackages.vendor, function(index) {
        var row = formatTableRow({
          "vendor": {
            "value": installedPackages.vendor[index],
            "size": colVendorSize
          },
          "name": {
            "value": installedPackages.name[index],
            "size": colNameSize
          },
          "version": {
            "value": installedPackages.version[index],
            "size": colVersionSize
          }
        });

        // append the generated row to the buffer
        buffer += row;

        // in case the generated row is the header, append a dash line of the row length (minus carriage-return
        if (index === 0)
          buffer += '| ' + padString('', '-', colVendorSize) + ' | ' + padString('', '-', colNameSize) + ' | ' +
              padString('', '-', colVersionSize) + " |\r\n";
      });

      buffer += "```";

      // copy the formatted markdown list to the clipboard
      $('body').append('<textarea id="copiedVersions"></textarea>');
      $('#copiedVersions').val(buffer);
      document.getElementById('copiedVersions').select();
      document.execCommand('copy');
      document.getElementById('copiedVersions').remove();

      copyVersions.attr('data-original-title', 'Copied!').tooltip('show');
    }).on('mouseleave', function(){
      copyVersions.attr('data-original-title', 'Click to copy packages versions');
    });

    $('.version-check').each(function(index, item) {
      var toCheckPackage = $(item);

      // fill the packages global variable
      installedPackages.vendor.push(toCheckPackage.attr('data-vendor'));
      installedPackages.name.push(toCheckPackage.attr('data-name'));
      installedPackages.version.push(toCheckPackage.attr('data-version'));

      // skip third party checks
      if (toCheckPackage.attr('data-vendor') == 'ccp')
        return false;

      // send a request to check if or not a package is up-to-date
      $.ajax({
        url: '{{ route('seatcore::packages.check') }}',
        method: 'POST',
        data: {
            'vendor': toCheckPackage.attr('data-vendor'),
            'package': toCheckPackage.attr('data-name'),
            'version': toCheckPackage.attr('data-version')
        }
      }).done(function (data) {
        // remove pending state from the package
        toCheckPackage.removeClass('fa-question-circle');
        toCheckPackage.removeClass('text-orange');
        // update package state according to result
        toCheckPackage.addClass(data.outdated ? 'fa-times-circle' : 'fa-check-circle');
        toCheckPackage.addClass(data.outdated ? 'text-red' : 'text-green');
        toCheckPackage.attr('title', data.outdated ? 'At least one new version has been released !' : 'The package is up-to-date.');
        toCheckPackage.attr('data-original-title', data.outdated ? 'At least one new version has been released !' : 'The package is up-to-date.');

        // updating counters
        $('#checking-packages').text(parseInt($('#checking-packages').text()) - 1);
        if (data.outdated)
          $('#outdated-packages').text(parseInt($('#outdated-packages').text()) + 1);
        else
          $('#updated-packages').text(parseInt($('#updated-packages').text()) + 1);

      });
    });

    $('#changelogModal').on('show.bs.modal', function (e) {
      var changelogMetadata = $(e.relatedTarget);
      var changelogModal = $(this);
      var changelogName = changelogMetadata.data('name');

      changelogModal.find('.modal-title span').text(changelogName);
      changelogModal.find('.modal-body').html('');

      $.ajax({
        url: '{{ route('seatcore::packages.changelog') }}',
        method: 'POST',
        data: changelogMetadata.data(),
        beforeSend: function () {
          changelogModal.find('.modal-content').append('<div class="overlay dark d-flex justify-content-center align-items-center"><i class="fas fa-2x fa-sync-alt fa-spin"></i> Loading...</div>');
        },
        success: function (data) {
          var body = $(data);

          // format tables
          body.find('table').addClass('table');

          // load modal content
          changelogModal.find('.modal-body').html(body);
          changelogModal.find('.overlay').remove();
        },
        error: function(xhr) { // if error occured
          alert("Error occured. please try again");
          changelogModal.find('.modal-body').html(xhr.statusText + xhr.responseText);
        }
      });
    });

    function getLongestString(column) {
      // clone the column in order to keep it unchanged
      var buffer = JSON.parse(JSON.stringify(column));
      // return the longest size
      return buffer.sort(function (a, b) { return b.length - a.length; }).shift().length;
    }

    function padString(string, char, size) {
      if (string.length >= size) return string;

      var end = string.length;
      for (i = 0; i < size - end; i++)
          string += char;

      return string;
    }

    function formatTableRow(row) {
      return '| ' +
        padString(row.vendor.value, ' ', row.vendor.size) + ' | ' +
        padString(row.name.value, ' ', row.name.size) + ' | ' +
        padString(row.version.value, ' ', row.version.size) + " |\r\n";
    }
  });
</script>
@endpush
