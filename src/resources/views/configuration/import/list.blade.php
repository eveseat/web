@extends('web::layouts.grids.4-8')

@section('title', 'Import')
@section('page_header', 'Import')

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Import Eve Online API Keys</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('configuration.import.csv') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="box-body">

          <div class="form-group">
            <label for="file">CSV Data Source</label>
            <input type="file" name="csv" class="form-control" id="file">
          </div>

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-primary pull-right">
            Import
          </button>
        </div>
      </form>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Help</h3>
    </div>
    <div class="panel-body">


      <dl>
        <dt>Importing Csv Data</dt>
        <dd>It is possible to import a CSV with API key data. The file format for the CSV is <code>keyID,vCode</code> and sampled below:</dd>
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
        <dt>Important Notes</dt>
        <dd>
          <ul>
            <li>The current user ({{ auth()->user()->name }}) will be come the owner of the API keys.</li>
            <li>Only API key format is checked here. No checking is done to ensure that minimum api masks are configured.</li>
            <li>Key data will only populate once the next updater job comes by, or is manuall started.</li>
          </ul>
        </dd>
      </dl>

    </div>
  </div>

@stop
