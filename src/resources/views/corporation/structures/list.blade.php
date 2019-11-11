@extends('web::corporation.layouts.view', ['viewname' => 'structures', 'breadcrumb' => trans_choice('web::seat.structure', 2)])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.structure', 2))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ trans_choice('web::seat.structure', 2) }}</h3>
        </div>
        <div class="card-body">

          <table class="table datatable compact table-condensed table-hover">
            <thead>
              <tr>
                <th>{{ trans_choice('web::seat.type', 1) }}</th>
                <th>{{ trans_choice('web::seat.location', 1) }}</th>
                <th>{{ trans('web::seat.state') }} </th>
                <th>{{ trans_choice('web::seat.offline', 1) }}</th>
                <th>{{ trans('web::seat.reinforce_week_hour') }}</th>
                <th data-orderable="false"></th>
                <th data-orderable="false"></th>
              </tr>
            </thead>
            <tbody>

              @foreach($structures as $structure)

                <tr>
                  <td>
                    @include('web::partials.type', ['type_id' => $structure->type->typeID, 'type_name' => $structure->type->typeName])
                  </td>
                  <td>
                    {{ $structure->system->itemName }}
                  </td>
                  <td>
                    {{ ucfirst(str_replace('_', ' ', $structure->state)) }}
                  </td>
                  <td data-sort="{{ $structure->fuel_expires }}">
                      <span data-toggle="tooltip" title="" data-original-title="{{ $structure->fuel_expires }}">
                        {{ human_diff($structure->fuel_expires) }}
                      </span>
                  </td>
                  <td>
                    <span data-toggle="tooltip" title=""
                          data-original-title="Weekday: {{ $structure->reinforce_weekday }} | Hour: {{ $structure->reinforce_hour }}">
                      {{ $structure->reinforce_weekday }}/{{ $structure->reinforce_hour }}
                    </span>
                  </td>
                  <td>
                    <ul>
                      @foreach($structure->services as $service)
                        <li>
                          {{ $service->name }} :
                          @if($service->state == 'online')
                            <span class="text text-green">
                              {{ ucfirst($service->state) }}
                            </span>
                          @else
                            <span class="text text-red">
                              {{ ucfirst($service->state) }}
                            </span>
                          @endif
                        </li>
                      @endforeach
                    </ul>
                  </td>
                  <td>
                    @include('web::corporation.structures.buttons.detail', [
                      'corporation_id' => $structure->corporation_id,
                      'structure_id' => $structure->structure_id
                    ])

                    @include('web::corporation.structures.buttons.export', ['data_export' => $structure->toEve()])
                  </td>
                </tr>

              @endforeach

            </tbody>
          </table>

        </div>
      </div>

    </div>
  </div> <!-- ./row -->

  @include('web::corporation.structures.modals.fitting.fitting')

@stop

@push('javascript')
  <script>
      $('#fitting-detail').on('show.bs.modal', function (e) {
          var body = $(e.target).find('.modal-body');
          body.html('Loading...');

          $.ajax($(e.relatedTarget).data('url'))
              .done(function (data) {
                  body.html(data);
                  $(document).find('span[data-toggle="tooltip"]').tooltip();
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
