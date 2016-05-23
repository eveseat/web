@extends('web::corporation.layouts.view', ['viewname' => 'summary'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.summary'))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.summary'))

@section('corporation_content')

  <div class="row">

    <div class="col-md-6">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::seat.summary') }}</h3>
        </div>
        <div class="panel-body">

          <dl>
            <dt>{{ trans('web::seat.shares') }}</dt>
            <dd>{{ $sheet->shares }}</dd>

            <dt>{{ trans('web::seat.member_capacity') }}</dt>
            <dd>{{ number( ($sheet->memberCount / $sheet->memberLimit) * 100 ,2) }}% Full</dd>
          </dl>

        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::seat.description') }}</h3>
        </div>
        <div class="panel-body">

          {!! clean_ccp_html($sheet->description) !!}

        </div>
      </div>
    </div>
    <div class="col-md-6">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::seat.divisional_information') }}</h3>
        </div>
        <div class="panel-body">

          <ul>
            <li class="list-header list-unstyled">{{ trans('web::seat.corporation_divisions') }}</li>
            @foreach($divisions as $division)
              <li>{{ $division->description }}</li>
            @endforeach

            <li class="list-header list-unstyled">{{ trans('web::seat.wallet_divisions') }}</li>
            @foreach($wallet_divisions as $wallet_division)
              <li>{{ $wallet_division->description }}</li>
            @endforeach
          </ul>

        </div>
      </div>

    </div>

  </div>


@stop
