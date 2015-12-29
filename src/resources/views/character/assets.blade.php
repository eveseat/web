@extends('web::character.layouts.view', ['viewname' => 'assets'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.assets'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.assets'))

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.assets') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive">
        <thead>
          <tr>
            <th>{{ trans('web::seat.quantity') }}</th>
            <th>{{ trans_choice('web::seat.type', 1) }}</th>
            <th>{{ trans('web::seat.volume') }}</th>
            <th>{{ trans('web::seat.group') }}</th>
          </tr>
        </thead>

        <tbody>
        @foreach($assets->unique('location')->groupBy('location') as $location => $data)

          <tr class="active">
            <td colspan="4">
              <b>{{ $location }}</b>
              <span class="pull-right">
                <i>
                {{ count($assets->where('locationID', $data[0]->locationID)) }}
                  {{ trans('web::seat.items_taking') }}
                {{ number_metric($assets
                    ->where('locationID', $data[0]->locationID)->map(function($item) {
                      return $item->quantity * $item->volume;
                    })->sum()) }} m&sup3;
                </i>
              </span>
            </td>
          </tr>

          @foreach($assets->where('locationID', $data[0]->locationID) as $asset)

            <tr>
              <td>{{ $asset->quantity }}</td>
              <td>
                {!! img('type', $asset->typeID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
                {{ $asset->typeName }}
              </td>
              <td>{{ number_metric($asset->quantity * $asset->volume) }} m&sup3;</td>
              <td>{{ $asset->groupName }}</td>
            </tr>

          @endforeach

        @endforeach
        </tbody>
      </table>

    </div><!-- /.box-body -->
  </div>

@stop
