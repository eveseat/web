@extends('web::character.layouts.view', ['viewname' => 'pi'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.pi'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.pi'))

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.pi') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table datatable compact table-condensed table-hover table-responsive">
        <thead>
        <tr>
          <th>{{ trans('web::seat.updated') }}</th>
          <th>{{ trans('web::seat.system') }}</th>
          <th>{{ trans('web::seat.planet') }}</th>
          <th>{{ trans('web::seat.upgrade_level') }}</th>
          <th>{{ trans('web::seat.no_pins') }}</th>
        </tr>
        </thead>
        <tbody>

        @foreach($colonies as $colony)

          <tr>
            <td data-order="{{ $colony->last_update }}">
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $colony->last_update }}">
                {{ human_diff($colony->last_update) }}
              </span>
            </td>
            <td>{{ $colony->itemName }}</td>
            <td>
              {!! img('type', $colony->typeID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ ucfirst($colony->planet_type) }}
            </td>
            <td>{{ $colony->upgrade_level }}</td>
            <td>{{ $colony->num_pins }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>


  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Extractors</h3>
    </div>
    <div class="panel-body">

      <table class="table datatable compact table-condensed table-hover table-responsive">
        <thead>
        <tr>
          <th>Planet</th>
          <th>Location</th>
          <th>Product</th>
          <th>Progress</th>
          <th>TimeLeft</th>
        </tr>
        </thead>
        <tbody>

        @foreach($extractors as $extractor)

          <tr>
            <td>
              {!! img('type', $extractor->typeID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ ucfirst($extractor->planet_type) }}
            </td>
            <td>{{ $extractor->itemName . " " . $extractor->celestialIndex }}</td>
            <td>
              {!! img('type', $extractor->product_type_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ ucfirst($extractor->typeName) }}
            </td>
            <td>
              @if($extractor->cycle_time < 30)
                <div class="progress active">
                  <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar"
                       aria-valuenow="{{$extractor->cycle_time}}" aria-valuemin="0" aria-valuemax="100"
                       style="width: {{$extractor->cycle_time}}%">
                    <span class="sr-only">{{$extractor->cycle_time}}% Complete (success)</span>
                  </div>
                </div>
              @elseif($extractor->cycle_time > 30 && $extractor->cycle_time < 80 )
                <div class="progress active">
                  <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                       aria-valuenow="{{$extractor->cycle_time}}" aria-valuemin="0" aria-valuemax="100"
                       style="width: {{$extractor->cycle_time}}%">
                    <span class="sr-only">{{$extractor->cycle_time}}% Complete (success)</span>
                  </div>
                </div>
              @elseif($extractor->cycle_time > 80 && $extractor->cycle_time < 100 )
                <div class="progress active">
                  <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar"
                       aria-valuenow="{{$extractor->cycle_time}}" aria-valuemin="0" aria-valuemax="100"
                       style="width: {{$extractor->cycle_time}}%">
                    <span class="sr-only">{{$extractor->cycle_time}}% Complete (success)</span>
                  </div>
                </div>
              @elseif($extractor->cycle_time >100)
                <div class="progress">
                  <div class="progress-bar progress-bar-red" role="progressbar"
                       aria-valuenow="{{$extractor->cycle_time}}" aria-valuemin="0" aria-valuemax="100"
                       style="width: {{$extractor->cycle_time}}%">
                    <span class="sr-only">{{$extractor->cycle_time}}% Complete (success)</span>
                  </div>
                </div>
              @endif
            </td>
            <td>
              @if (date_diff(date_create_from_format('U',strtotime($extractor->expiry_time)),now())->invert === 0)
                (expired)
              @else
                {!! date_diff(date_create_from_format('U',strtotime($extractor->expiry_time)),now())->d . ' days, ' .
                date_diff(date_create_from_format('U',strtotime($extractor->expiry_time)),now())->h . ' hours and ' .
                 date_diff(date_create_from_format('U',strtotime($extractor->expiry_time)),now())->s . 'secounds left'!!}
              @endif
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>



@stop
