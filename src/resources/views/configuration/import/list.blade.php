@extends('web::layouts.grids.4-8')

@section('title', trans('web::seat.import'))
@section('page_header', trans('web::seat.import'))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.api_import_title') }}</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('configuration.import.csv') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="box-body">

          <div class="form-group">
            <label for="file">{{ trans('web::seat.csv_data_source') }}</label>
            <input type="file" name="csv" class="form-control" id="file">
          </div>

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-primary float-right">
            {{ trans('web::seat.import') }}
          </button>
        </div>
      </form>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.help') }}</h3>
    </div>
    <div class="panel-body">


      <dl>
        <dt>{{ trans('web::seat.importing_csv_data') }}</dt>
        <dd>{{ trans('web::seat.csv_format_explained') }}:</dd>
      </dl>

      <pre>234235,DbvDztdYPiLJm9D0JXGqopIhlcED2YIg0KF0uZc85Sc9cBtFhr7gAciu8e8U8uwI
123324,tVatyy13IN9cCJ4wXlykyRNUrm34ARDKQlTA95wFPg3142rLbCF82gu0o1ofOPyU
543535,YL0ROiGTBfoQAa4Otmi2iowdalafe5RsFvCYvis7YND0ZKLt6B4vYWGfgDIRTXOt
524324423,p70j5Whli1GZKDsYARVZNfyTQZMNgCgldQaDAQOFJHB1Di2X0q5BUdYyRtF64T9q
423243234,iL7rRMVwZrtO9JTC9fry6s9ZMZx7WJkWSenJuXPj3WC2cEnMhGCLRLXUBM05MlvV
5456342,L1NlZgAP1D4fqioCZm7hbQEMwFQ3u2uVnNNsQuXFkdqUqPxm2GukkGWLdXcTkIGR
9382342,AJ3KVCF6NGxGJd9wP1kI8U5OwXMivxXXKPJUuCnoE1EikGB9GTIQ5PeKZPsJFVFm
3424623,eyre7az1xckrujcPCS6Uml1YykAELINWWipKvmjlPHNcZYsDCyQkXvFf29TolwnC
1245,IcxRQqDjkr4UDiZujKIdDISFSkmNfljHYwtLh5irnuK61ChUEBILaojJRnkULnot
235463456,RRNYnGMEbPwAdmG1d0fGb7aRwQdnK9PZmpPCIoAV30y139AYVGkZJhFKxv3BXeEY</pre>

      <dl>
        <dt>{{ trans('web::seat.important_notes') }}</dt>
        <dd>
          <ul>
            <li>{{ trans('web::seat.curr_user_becomes_owner', ['user' => auth()->user()->name]) }}</li>
            <li>{{ trans('web::seat.only_format_is_checked') }}</li>
            <li>{{ trans('web::seat.update_with_next_job') }}</li>
          </ul>
        </dd>
      </dl>

    </div>
  </div>

@stop
