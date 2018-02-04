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
              @if($type == 'npc_corp')
              <b>Corporation NPC</b>
              @else
              <b>{{ ucfirst($type) }}</b>
              @endif
            </td>
          </tr>

          @foreach($standings->where('from_type', $data[0]->from_type) as $standing)

            <tr>
              <td>
                {!! img('auto', $standing->from_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                @if(is_null($standing->factionName))
                <span rel="id-to-name">{{ $standing->from_id }}</span>
                @else
                {{ $standing->factionName }}
                @endif
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
