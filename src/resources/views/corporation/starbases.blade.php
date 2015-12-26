@extends('web::corporation.layouts.view', ['viewname' => 'starbases'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.starbase', 2))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.starbase', 2))

@section('corporation_content')

  <div class="row">
    <div class="col-md-12">

      @include('web::corporation.starbase.summary')

    </div>
  </div> <!-- ./row -->

  @foreach($starbases as $starbase)

    <div class="row">
      <div class="col-md-12">

        <div class="panel panel-default" id="starbaseDetail{{ $starbase->itemID }}">
          <div class="panel-heading">
            <h3 class="panel-title">
              {{ $starbase->starbaseName }} <i>({{ $starbase->moonName }})</i>
            </h3>
          </div>
          <div class="panel-body">
            <div>

              <!-- Nav tabs -->
              <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                  <a href="#status{{ $starbase->itemID }}" aria-controls="status{{ $starbase->itemID }}"
                     role="tab" data-toggle="tab">Status</a>
                </li>
                <li role="presentation">
                  <a href="#modules{{ $starbase->itemID }}" aria-controls="modules{{ $starbase->itemID }}"
                     role="tab" data-toggle="tab">{{ trans_choice('web::seat.module', 2) }}</a>
                </li>
              </ul>

              <div class="tab-content">

                @include('web::corporation.starbase.status-tab')

                @include('web::corporation.starbase.modules-tab')

              </div>

            </div>
          </div>
        </div>

      </div>
    </div>

  @endforeach

@stop

@section('javascript')

  @include('web::includes.javascript.id-to-name')

@stop
