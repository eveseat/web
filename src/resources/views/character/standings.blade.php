@extends('web::character.layouts.view', ['viewname' => 'standings'])

@section('title', ucfirst(trans_choice('web::character.character', 1)) . ' Standings')
@section('page_header', ucfirst(trans_choice('web::character.character', 1)) . ' Standings')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Standings</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>From</th>
          <th>Standing</th>
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
