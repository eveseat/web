@extends('web::corporation.layouts.view', ['viewname' => 'structures'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.starbase', 2))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.starbase', 2))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="row">
    <div class="col-md-12">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans_choice('web::seat.structure', 2) }}</h3>
        </div>
        <div class="panel-body">

          <table class="table datatable compact table-condensed table-hover table-responsive">
            <thead>
            <tr>
              <th>{{ trans_choice('web::seat.type', 1) }}</th>
              <th>{{ trans_choice('web::seat.location', 1) }}</th>
              <th>{{ trans('web::seat.state') }} </th>
              <th>{{ trans_choice('web::seat.offline', 1) }}</th>
              <th>{{ trans('web::seat.reinforce_week_hour') }}</th>
              <th data-orderable="false"></th>
            </tr>
            </thead>
            <tbody>

            @foreach($structures as $structure)

              <tr>
                <td>
                  {!! img('type', $structure->type->typeID, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                  {{ $structure->type->typeName }}
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

              </tr>

            @endforeach

            </tbody>
          </table>

        </div>
      </div>

    </div>
  </div> <!-- ./row -->

@stop
