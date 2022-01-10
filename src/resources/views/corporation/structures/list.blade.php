@extends('web::layouts.corporation', ['viewname' => 'structures', 'breadcrumb' => trans_choice('web::seat.structure', 2)])

@section('page_description', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.upwell_structure', 2))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ trans_choice('web::seat.structure', 2) }}</h3>
          <div class="card-tools">
            <div class="input-group input-group-sm">
                @include('web::components.jobs.buttons.update', ['type' => 'corporation', 'entity' => $corporation->corporation_id, 'job' => 'corporation.structures', 'label' => trans('web::seat.update_structures')])
            </div>
          </div>
        </div>
        <div class="card-body">

          {{ $dataTable->table() }}

        </div>
      </div>

    </div>
  </div> <!-- ./row -->

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
