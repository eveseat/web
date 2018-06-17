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
              <div class="countdown-progressbar" data-expirytime="{{$extractor['expiry_time']}}"
                   data-installtime="{{$extractor['install_time']}}"></div>
            </td>
            <td>
              <div class="countdown" data-expirytime="{{$extractor['expiry_time']}}">{{$extractor['expiry_time']}}</div>
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
          $(this).text(moment.utc($(this).attr('data-expirytime'), "YYYY-MM-DD hh:mm:ss").calendar());
        });
      }

      function updateProgressBar() {
        $(".countdown-progressbar").each(function () {
          var expirytime = moment.utc($(this).attr('data-expirytime'), "YYYY-MM-DD hh:mm:ss").valueOf();
          var installtime = moment.utc($(this).attr('data-installtime'), "YYYY-MM-DD hh:mm:ss").valueOf();
          var percentage_complete = (moment.utc() - installtime) / (expirytime - installtime);
          var percentage_rounded = (Math.round(percentage_complete * 100) / 100);
          if (percentage_rounded < 0.4) {
            $(this).html(
                "<div class='progress active'>" +
                '<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"' +
                'aria-valuenow="' + percentage_rounded * 100 + '" aria-valuemin="0" aria-valuemax="100" ' +
                'style="width: ' + percentage_rounded * 100 + '%" >' +
                100 * percentage_rounded +
                '%</div>' +
                '</div>'
            )
          } else if (percentage_rounded >= 0.4 && percentage_rounded < 0.6) {
            $(this).html(
                "<div class='progress active'>" +
                '<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar"' +
                'aria-valuenow="' + percentage_rounded * 100 + '" aria-valuemin="0" aria-valuemax="100" ' +
                'style="width: ' + percentage_rounded * 100 + '%" >' +
                100 * percentage_rounded +
                '%</div>' +
                '</div>'
            )
          } else if (percentage_rounded >= 0.6 && percentage_rounded < 0.8) {
            $(this).html(
                "<div class='progress active'>" +
                '<div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar"' +
                'aria-valuenow="' + percentage_rounded * 100 + '" aria-valuemin="0" aria-valuemax="100" ' +
                'style="width: ' + percentage_rounded * 100 + '%" >' +
                100 * percentage_rounded +
                '%</div>' +
                '</div>'
            )
          } else if (percentage_rounded >= 0.8 && percentage_rounded < 1) {
            $(this).html(
                "<div class='progress active'>" +
                '<div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar"' +
                'aria-valuenow="' + percentage_rounded * 100 + '" aria-valuemin="0" aria-valuemax="100" ' +
                'style="width: ' + percentage_rounded * 100 + '%" >' +
                100 * percentage_rounded +
                '%</div>' +
                '</div>'
            )
          } else if (percentage_rounded >= 1) {
            $(this).html(
                "<div class='progress active'>" +
                '<div class="progress-bar progress-bar-danger" role="progressbar"' +
                'aria-valuenow="' + percentage_rounded * 100 + '" aria-valuemin="0" aria-valuemax="100" ' +
                'style="width: ' + percentage_rounded * 100 + '%" >' +
                100 * percentage_rounded +
                '%</div>' +
                '</div>'
            )
          }
        })
      }

      updateExpiryText();
      updateProgressBar();
      setInterval(function () { //this is to update every 5 secounds
        updateExpiryText();
        updateProgressBar();
      }, 133700);

    });
  </script>

@endpush
