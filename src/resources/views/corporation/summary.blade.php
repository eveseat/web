@extends('web::corporation.layouts.view', ['viewname' => 'summary'])

@section('title', ucfirst(trans_choice('web::corporation.corporation', 1)) . ' Summary')
@section('page_header', ucfirst(trans_choice('web::corporation.corporation', 1)) . ' Summary')

@section('corporation_content')

  <div class="row">

    <div class="col-md-6">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Summary</h3>
        </div>
        <div class="panel-body">

          <dl>
            <dt>Shares</dt>
            <dd>{{ $sheet->shares }}</dd>

            <dt>Member Capacity</dt>
            <dd>{{ number( ($sheet->memberCount / $sheet->memberLimit) * 100 ,2) }}% Full</dd>
          </dl>

        </div>
        <div class="panel-footer">Footer</div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Description</h3>
        </div>
        <div class="panel-body">

          {!! clean_ccp_html($sheet->description) !!}

        </div>
      </div>
    </div>
    <div class="col-md-6">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Divisional Information</h3>
        </div>
        <div class="panel-body">

          <ul>
            <li class="list-header list-unstyled">Corporation Divisions</li>
            @foreach($divisions as $division)
              <li>{{ $division->description }}</li>
            @endforeach

            <li class="list-header list-unstyled">Corporation Wallet Divisions</li>
            @foreach($wallet_divisions as $wallet_division)
              <li>{{ $wallet_division->description }}</li>
            @endforeach
          </ul>

        </div>
      </div>

    </div>

  </div>


@stop
