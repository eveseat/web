@extends('web::character.layouts.view', ['viewname' => 'journal'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.wallet_journal'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.wallet_journal'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="row">
    <div class="col-md-12">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans_choice('web::seat.filter', 2) }}</h3>
        </div>
        <div class="panel-body">


          <form role="form" action="{{ route('character.view.journal', ['character_id' => $request->character_id]) }}"
                method="get">

            <div class="box-body">

              <div class="form-group">
                <label>{{ trans('web::seat.transaction_type') }}</label>
                <select id="refTypeName" class="form-control" name="filter[refTypeName][]" multiple>

                  @foreach($transaction_types as $type)

                    <option value="{{ $type->refTypeName }}"
                            @if(isset($request->filter['refTypeName']))
                              @if(in_array($type->refTypeName, $request->filter['refTypeName'])))
                                selected
                              @endif
                            @endif>
                      {{ $type->refTypeName }}
                    </option>

                  @endforeach

                </select>
              </div>

            </div>
            <!-- /.box-body -->

            <div class="box-footer">

              <a href="{{ route('character.view.journal', ['character_id' => $request->character_id]) }}"
                 class="btn btn-warning pull-left">
                {{ trans('web::seat.clear') }}
              </a>

              <button type="submit" class="btn btn-primary pull-right">
                {{ trans_choice('web::seat.filter', 1) }}
              </button>

            </div>

          </form>
        </div>

        @if($request->filter)

          <div class="panel-footer">

            @foreach($request->filter as $name => $filter)

              <div class="text-muted">{{ studly_case($name) }}</div>
              @foreach($filter as $detail)

                <span class="label label-default">{{ $detail }}</span>

              @endforeach

            @endforeach

          </div>

        @endif

      </div>

    </div>
  </div>

  <div class="row">
    <div class="col-md-12">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::seat.wallet_journal') }}</h3>
        </div>
        <div class="panel-body">

          <table class="table table-condensed table-hover">
            <tbody>
            <tr>
              <th>{{ trans('web::seat.date') }}</th>
              <th>{{ trans_choice('web::seat.type', 1) }}</th>
              <th>{{ trans('web::seat.owner_1') }}</th>
              <th>{{ trans('web::seat.owner_2') }}</th>
              <th>{{ trans('web::seat.amount') }}</th>
              <th>{{ trans('web::seat.balance') }}</th>
            </tr>

            @foreach($journal as $transaction)

              <tr>
                <td>{{ human_diff($transaction->date) }}</td>
                <td>
                  {{ $transaction->refTypeName }}
                  @if($transaction->argName1)
                    <i class="fa fa-info-circle pull-right" data-toggle="tooltip"
                       title="" data-original-title="{{ $transaction->argName1 }}">
                    </i>
                  @endif
                </td>
                <td>
                  @if($transaction->ownerID1 != 0)
                    {!! img('auto', $transaction->ownerID1, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
                    {{ $transaction->ownerName1 }}
                  @else
                    n/a
                  @endif
                </td>
                <td>
                  @if($transaction->ownerID2 != 0)
                    {!! img('auto', $transaction->ownerID2, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
                    {{ $transaction->ownerName2 }}
                  @else
                    n/a
                  @endif
                </td>
                <td>{{ number($transaction->amount) }}</td>
                <td>{{ number($transaction->balance) }}</td>
              </tr>

            @endforeach

            </tbody>
          </table>

        </div>
        <div class="panel-footer">Footer</div>
      </div>

    </div>
  </div>

@stop

@section('javascript')

  <script>
    $("#refTypeName").select2();
  </script>

@stop
