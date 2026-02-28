@extends('web::corporation.layouts.view', ['viewname' => 'projects', 'breadcrumb' => trans_choice('web::seat.project', 2)])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.project', 2))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ trans_choice('web::seat.project', 2) }}</h3>
          <div class="card-tools">
            <div class="input-group input-group-sm">
                @include('web::components.jobs.buttons.update', ['type' => 'corporation', 'entity' => $corporation->corporation_id, 'job' => 'corporation.projects', 'label' => trans('web::seat.update_projects')])
            </div>
          </div>
        </div>
        <div class="card-body">

          {{ $dataTable->table() }}

        </div>
      </div>

    </div>
  </div> <!-- ./row -->

 @include('web::corporation.projects.modals.details')

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}

  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })

  <script>
    $('#project-detail').on('show.bs.modal', function (e) {
        var body = $(e.target).find('.modal-body');
        body.html('Loading...');

        $.ajax($(e.relatedTarget).data('url'))
            .done(function (data) {
                body.html(data);
                $(document).find('span[data-toggle="tooltip"]').tooltip();
            });
    });
  </script>

@endpush
