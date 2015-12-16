@extends('web::character.layouts.view', ['viewname' => 'industry'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.industry'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.industry'))

@inject('carbon', 'Carbon\Carbon')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.industry') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>{{ trans('web::seat.start') }}</th>
          <th>{{ trans('web::seat.installer') }}</th>
          <th>{{ trans('web::seat.system') }}</th>
          <th>{{ trans('web::seat.activity') }}</th>
          <th>{{ trans_choice('web::seat.run', 2) }}</th>
          <th>{{ trans('web::seat.blueprint') }}</th>
          <th>{{ trans('web::seat.product') }}</th>
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
