@extends('web::corporation.layouts.view', ['viewname' => 'standings', 'breadcrumb' => trans('web::seat.standings')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.standings'))

@section('corporation_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.standings') }}</h3>
    </div>
    <div class="panel-body no-padding">

      @foreach($standings->unique('from_type')->groupBy('from_type') as $type => $data)

        <div class="box box-solid">
          <div class="box-header with-border">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
              <i class="fa fa-minus"></i>
            </button>
            @if($type == 'npc_corp')
              <h3 class="box-title">Corporation NPC</h3>
            @else
              <h3 class="box-title">{{ ucfirst($type) }}</h3>
            @endif
          </div>
          <div class="box-body">
            <table class="table table-striped table-responsive table-condensed table-hover">
              <thead>
              <tr>
                <th>{{ trans('web::seat.from') }}</th>
                <th>{{ trans('web::seat.standings') }}</th>
              </tr>
              </thead>
              <tbody>
              @foreach ($standings->where('from_type', $data[0]->from_type) as $standing)
                <tr>
                  <td>
                    {!! img('auto', $standing->from_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                    @if(is_null($standing->factionName))
                      <span class="id-to-name"
                            data-id="{{ $standing->from_id }}">{{ trans('web::seat.unknown') }}</span>
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
              </tbody>
            </table>
          </div>
        </div>

      @endforeach
    </div>
    <div class="panel-footer">
      {!! $standings->render() !!}
    </div>
  </div>

@stop
