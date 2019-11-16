@extends('web::character.layouts.view', ['viewname' => 'pi', 'breadcrumb' => trans('web::seat.pi')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.pi'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        {{ trans('web::seat.pi') }}
      </h3>
      @if(auth()->user()->has('character.jobs'))
        <div class="card-tools">
          <div class="input-group input-group-sm">
            <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.pi']) }}"
               class="btn btn-sm btn-light">
              <i class="fas fa-sync" data-toggle="tooltip" title="{{ trans('web::seat.update_pi') }}"></i>
            </a>
          </div>
        </div>
      @endif
    </div>
    <div class="card-body">

      <table class="table datatable table-sm table-condensed table-striped table-hover">
        <thead>
          <tr>
            <th>{{ trans('web::seat.updated') }}</th>
            <th>{{ trans('web::seat.system') }}</th>
            <th>{{ trans('web::seat.planet') }}</th>
            <th>{{ trans('web::seat.upgrade_level') }}</th>
            <th>{{ trans('web::seat.no_pins') }}</th>
          </tr>
        </thead>
        <tbody>

          @foreach($colonies as $colony)

            <tr>
              <td data-order="{{ $colony->last_update }}">
                <span data-toggle="tooltip" title="" data-original-title="{{ $colony->last_update }}">
                  {{ human_diff($colony->last_update) }}
                </span>
              </td>
              <td>{{ $colony->itemName }}</td>
              <td>
                @include('web::partials.type', ['type_id' => $colony->typeID, 'type_name' => $colony->planet_type])
              </td>
              <td>{{ $colony->upgrade_level }}</td>
              <td>{{ $colony->num_pins }}</td>
            </tr>

          @endforeach

        </tbody>
      </table>

    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Extractors</h3>
    </div>
    <div class="card-body">

      <table class="table datatable table-sm table-condensed table-striped table-hover">
        <thead>
          <tr>
            <th>{{ trans('web::seat.planet') }}</th>
            <th>{{ trans('web::seat.system') }}</th>
            <th>{{ trans('web::seat.product') }}</th>
            <th>{{ trans('web::seat.progress') }}</th>
            <th>{{ trans('web::seat.expiry') }}</th>
          </tr>
        </thead>
        <tbody>

          @foreach($extractors as $extractor)

            <tr>
              <td>
                @include('web::partials.type', ['type_id' => $extractor['typeID'], 'type_name' => ucfirst($extractor['planet_type'])])
              </td>
              <td>{{ $extractor['itemName'] . " " . $extractor['celestialIndex'] }}</td>
              <td>
                @include('web::partials.type', ['type_id' => $extractor['product_type_id'], 'type_name' => ucfirst($extractor['typeName'])])
              </td>
              <td>
                <div class="countdown-progressbar" data-expiry-time="{{$extractor['expiry_time']}}"
                     data-install-time="{{$extractor['install_time']}}"></div>
              </td>
              <td>
                <div class="countdown" data-expiry-time="{{$extractor['expiry_time']}}">{{$extractor['expiry_time']}}</div>
              </td>
            </tr>

          @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop

@push('javascript')

  <script type="text/javascript">
    $(document).ready(function () {

      function updateExpiryText() {
        $(".countdown").each(function () {
          $(this).text(moment.utc($(this).attr('data-expiry-time'), "YYYY-MM-DD hh:mm:ss").calendar());
        });
      }

      function updateProgressBar() {
        $(".countdown-progressbar").each(function () {
          var expiry_time = moment.utc($(this).attr('data-expiry-time'), "YYYY-MM-DD hh:mm:ss").valueOf();
          var install_time = moment.utc($(this).attr('data-install-time'), "YYYY-MM-DD hh:mm:ss").valueOf();
          var percentage_complete = (moment.utc() - install_time) / (expiry_time - install_time);
          var percentage_rounded = (Math.round(percentage_complete * 100) / 100);
          var progress_value = percentage_rounded * 100;
          var progress_class = 'progress-bar progress-bar-striped';

          // ensure progress-bar will not exceed 100%
          if (progress_value > 100)
              progress_value = 100;

          // design progress-bar according to completion
          switch (true) {
              case (percentage_rounded < 0.4):
                  progress_class += ' progress-bar-success progress-bar-striped';
                  break;
              case (percentage_rounded >= 0.4 && percentage_rounded < 0.6):
                  progress_class += ' progress-bar-info progress-bar-striped';
                  break;
              case (percentage_rounded >= 0.6 && percentage_rounded < 0.8):
                  progress_class += ' progress-bar-warning progress-bar-striped';
                  break;
              case (percentage_rounded < 1):
                  progress_class += ' progress-bar-danger progress-bar-striped';
                  break;
              default:
                  progress_class += ' progress-bar-danger progress-bar-striped';
          }

          // draw the updated progress bar
          $(this).html(
              "<div class='progress active'>" +
              '<div class="' + progress_class + '" role="progressbar" ' +
              'aria-valuenow="' + progress_value + '" aria-valuemin="0" aria-valuemax="100" ' +
              'style="width: ' + progress_value + '%" >' + progress_value +
              '%</div>' +
              '</div>'
          );
        });
      }

      updateExpiryText();
      updateProgressBar();
      setInterval(function () { //this is to update every 15 seconds
        updateExpiryText();
        updateProgressBar();
      }, 15000);

    });
  </script>

@endpush
