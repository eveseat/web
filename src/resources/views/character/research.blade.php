@extends('web::character.layouts.view', ['viewname' => 'research', 'breadcrumb' => trans('web::seat.research')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.research'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans('web::seat.research') }}
        @if(auth()->user()->has('character.jobs'))
          <span class="pull-right">
            <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.research']) }}"
               style="color: #000000">
              <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_research') }}"></i>
            </a>
          </span>
        @endif
      </h3>
    </div>
    <div class="panel-body">

      <table class="table datatable compact table-condensed table-hover table-responsive">
        <thead>
        <tr>
          <th>{{ trans('web::seat.start') }}</th>
          <th>{{ trans('web::seat.agent') }}</th>
          <th>{{ trans_choice('web::seat.skill', 1) }}</th>
          <th>{{ trans('web::seat.points_p_day') }}</th>
          <th>{{ trans('web::seat.remainder') }}</th>
        </tr>
        </thead>
        <tbody>

        @foreach($agents as $agent)

          <tr>
            <td data-order="{{ $agent->started_at }}">
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $agent->started_at }}">
                {{ human_diff($agent->started_at) }}
              </span>
            </td>
            <td>
              {!! img('character', $agent->itemID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $agent->itemName }}
            </td>
            <td>
              {!! img('type', $agent->typeID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $agent->typeName }}
            </td>
            <td>{{ $agent->points_per_day }}</td>
            <td>{{ $agent->remainder_points }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
