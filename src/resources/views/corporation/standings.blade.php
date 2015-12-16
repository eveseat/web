@extends('web::corporation.layouts.view', ['viewname' => 'standings'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.standings'))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.standings'))

@section('corporation_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.standings') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>{{ trans('web::seat.from') }}</th>
          <th>{{ trans('web::seat.standings') }}</th>
        </tr>

        @foreach($standings->unique('type')->groupBy('type') as $type => $data)

          <tr class="active">
            <td colspan="2">
              <b>{{ ucfirst($type) }}</b>
            </td>
          </tr>

          @foreach($standings->where('type', $data[0]->type) as $standing)

            <tr>
              <td>
                {!! img('auto', $standing->fromID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                {{ $standing->fromName }}
              </td>
              <td>
                <span class="
                  @if($standing->standing > 0)
                        text-success
                      @else
                        text-danger
                      @endif">
                  {{ $standing->standing }}
                </span>
              </td>
            </tr>

          @endforeach

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
