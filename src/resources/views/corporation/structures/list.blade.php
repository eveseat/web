@extends('web::layouts.corporation', ['viewname' => 'structures', 'breadcrumb' => trans_choice('web::seat.structure', 2)])

@section('page_description', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.upwell_structure', 2))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="col-auto me-5">
                <h3 class="card-title">{{ trans_choice('web::seat.structure', 2) }}</h3>
            </div>
            <div class="ms-auto">
                @include('web::components.jobs.buttons.update', ['type' => 'corporation', 'entity' => $corporation->corporation_id, 'job' => 'corporation.structures', 'label' => trans('web::seat.update_structures')])
            </div>
        </div>

        {{ $dataTable->table(['class' => 'table card-table table-vcenter table-hover table-striped text-nowrap']) }}
    </div>

    @include('web::corporation.structures.modals.fitting.fitting')

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
  <script>
      $('#fitting-detail').on('show.bs.modal', function (e) {
          var body = $(e.target).find('.modal-body');
          body.html('Loading...');

          $.ajax($(e.relatedTarget).data('url'))
              .done(function (data) {
                  body.html(data);
                  $(document).find('span[data-bs-toggle="tooltip"]').tooltip();
              });
      });

      $(document).on('click', '.copy-fitting', function (e) {
          var buffer = $(this).data('export');

          $('body').append('<textarea id="copied-fitting"></textarea>');
          $('#copied-fitting').val(buffer);
          document.getElementById('copied-fitting').select();
          document.execCommand('copy');
          document.getElementById('copied-fitting').remove();

          $(this).attr('data-original-title', 'Copied !')
              .tooltip('show');

          $(this).attr('data-original-title', 'Copy to clipboard');
      });
  </script>
@endpush
