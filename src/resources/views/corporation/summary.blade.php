@extends('web::corporation.layouts.view', ['viewname' => 'summary', 'breadcrumb' => trans('web::seat.summary')])

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
            <dt><i class="fa fa-handshake-o"></i> {{ trans('web::seat.shares') }}</dt>
            <dd>{{ $sheet->shares }}</dd>

            <dt><i class="fa fa-users"></i> {{ trans('web::seat.member_capacity') }}</dt>
            <dd>{{ $sheet->member_count }} {{ trans_choice('web::seat.member', $sheet->member_count) }}</dd>
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

          <dl>
            <dt><i class="fa fa-cubes"></i> {{ trans('web::seat.corporation_divisions') }}</dt>
            <dd>
              <ol>
                @foreach($divisions as $division)
                  <li>{{ $division->name }}</li>
                @endforeach
              </ol>
            </dd>
          </dl>

          <dl>
            <dt><i class="fa fa-money"></i> {{ trans('web::seat.wallet_divisions') }}</dt>
            <dd>
              <ol>
                @foreach($wallet_divisions as $wallet_division)
                  @if(is_null($wallet_division->name))
                    <li>Master</li>
                  @else
                    <li>{{ $wallet_division->name }}</li>
                  @endif
                @endforeach
              </ol>
            </dd>
          </dl>

        </div>
      </div>

    </div>

  </div>


@stop
