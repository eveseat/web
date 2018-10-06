@extends('web::character.layouts.view', ['viewname' => 'standings'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.standings'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.standings'))

@section('character_content')

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

        @foreach($standings->unique('from_type')->groupBy('from_type') as $type => $data)

          <tr class="active">
            <td colspan="2">
              <b>{{ ucfirst($type) }}</b>
            </td>
          </tr>

          @foreach($standings->where('from_type', $data[0]->from_type) as $standing)

            <tr>
              <td>
                {!! img('auto', $standing->from_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                @if(is_null($standing->factionName))
                  <span class="id-to-name" data-id="{{$standing->from_id }}">{{ trans('web::seat.unknown') }}</span>
                @else
                  {{ $standing->factionName }}
                @endif
              </td>
              <td>
                @if($standing->standing > 5)
                  <span class="label label-primary">{{ $standing->standing }}</span>
                @elseif($standing->standing >= 1)
                  <span class="label label-info">{{ $standing->standing }}</span>
                @elseif($standing->standing > -1)
                  <span class="label label-default">{{ $standing->standing }}</span>
                @elseif($standing->standing >= -5)
                  <span class="label label-warning">{{ $standing->standing }}</span>
                @else
                  <span class="label label-danger">{{ $standing->standing }}</span>
                @endif
              </td>
            </tr>

          @endforeach

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
