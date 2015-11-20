@extends('web::character.layouts.view', ['viewname' => 'assets'])

@section('title', ucfirst(trans_choice('web::character.character', 1)) . ' Assets')
@section('page_header', ucfirst(trans_choice('web::character.character', 1)) . ' Assets')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Assets</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>Qty</th>
          <th>Type</th>
          <th>Volume</th>
          <th>Group</th>
        </tr>

      @foreach($assets->unique('location')->groupBy('location') as $location => $data)

        <tr class="active">
          <td colspan="4">
            <b>{{ $location }}</b>
            <span class="pull-right">
              <i>
              {{ count($assets->where('locationID', $data[0]->locationID)) }} items taking
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
