@extends('web::character.layouts.view', ['viewname' => 'bookmarks', 'breadcrumb' => trans_choice('web::seat.bookmark', 2)])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans_choice('web::seat.bookmark', 2))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans_choice('web::seat.bookmark', 2) }}
        @if(auth()->user()->has('character.jobs'))
          <span class="pull-right">
            <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.bookmarks']) }}"
               style="color: #000000">
              <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_bookmarks') }}"></i>
            </a>
          </span>
        @endif
      </h3>
    </div>
    <div class="panel-body">

      {{ $dataTable->table() }}

    </div>
  </div>

@stop

@push('javascript')

  {!! $dataTable->scripts() !!}

@endpush