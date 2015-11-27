@extends('web::corporation.layouts.view', ['viewname' => 'industry'])

@section('title', ucfirst(trans_choice('web::corporation.corporation', 1)) . ' Industry')
@section('page_header', ucfirst(trans_choice('web::corporation.corporation', 1)) . ' Industry')

@inject('carbon', 'Carbon\Carbon')

@section('corporation_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Industry</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>Start</th>
          <th>Installer</th>
          <th>System</th>
          <th>Activity</th>
          <th>Runs</th>
          <th>Bueprint</th>
          <th>Product</th>
          <th></th>
        </tr>

        @foreach($jobs as $job)

          <tr>
            <td>
              <span>
                <i class="fa
                    @if($carbon->parse($job->endDate)->gte($carbon->now()))
                        fa-clock-o
                      @else
                        fa-check
                      @endif"
                   data-toggle="tooltip"
                   title="" data-original-title="End: {{ $job->endDate }}">
                </i>
              </span>
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $job->startDate }}">
                {{ human_diff($job->startDate) }}
              </span>
            </td>
            <td>
              {!! img('character', $job->installerID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $job->installerName }}
            </td>
            <td>
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $job->facilityName }}">
                <i class="fa fa-map-marker"></i>
              </span>
              {{ $job->solarSystemName }}
            </td>
            <td>{{ $job->activityName }}</td>
            <td>{{ $job->runs }}</td>
            <td>
              {!! img('type', $job->blueprintTypeID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $job->blueprintTypeName }}
            </td>
            <td>
              {!! img('type', $job->productTypeID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $job->productTypeName }}
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
