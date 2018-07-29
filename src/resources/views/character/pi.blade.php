@extends('web::character.layouts.view', ['viewname' => 'pi'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.pi'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.pi'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans('web::seat.pi') }}
        @if(auth()->user()->has('character.jobs'))
          <span class="pull-right">
            <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.pi']) }}"
               style="color: #000000">
              <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_pi') }}"></i>
            </a>
          </span>
        @endif
      </h3>
    </div>
    <div class="panel-body">

      <table class="table datatable compact table-condensed table-hover table-responsive">
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
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $colony->last_update }}">
                {{ human_diff($colony->last_update) }}
              </span>
            </td>
            <td>{{ $colony->itemName }}</td>
            <td>
              {!! img('type', $colony->typeID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ ucfirst($colony->planet_type) }}
            </td>
            <td>{{ $colony->upgrade_level }}</td>
            <td>{{ $colony->num_pins }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Extractors</h3>
    </div>
    <div class="panel-body">

      <table class="table datatable compact table-condensed table-hover table-responsive">
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
              {!! img('type', $extractor['typeID'], 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ ucfirst($extractor['planet_type']) }}
            </td>
            <td>{{ $extractor['itemName'] . " " . $extractor['celestialIndex'] }}</td>
            <td>
              {!! img('type', $extractor['product_type_id'], 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ ucfirst($extractor['typeName']) }}
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
          var progress_class = 'progress-bar';

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
                  progress_class += ' progress-bar-danger';
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
