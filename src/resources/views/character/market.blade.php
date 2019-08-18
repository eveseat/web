@extends('web::character.layouts.view', ['viewname' => 'market', 'breadcrumb' => trans('web::seat.market')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.market'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans('web::seat.market') }}
        <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.market']) }}" class="pull-right">
          <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_market') }}"></i>
        </a>
      </h3>
    </div>
    <div class="panel-body">
      {!! $dataTable->table() !!}
    </div>
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush
