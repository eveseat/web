@extends('web::character.layouts.view', ['viewname' => 'transactions'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.wallet_transactions'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.wallet_transactions'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="row">
    <div class="col-md-12">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans_choice('web::seat.filter', 2) }}</h3>
        </div>
        <div class="panel-body">

          <form role="form"
                action="{{ route('character.view.transactions', ['character_id' => $request->character_id]) }}"
                method="get">

            <div class="box-body">

              <div class="row">
                <div class="col-md-6">

                  <div class="form-group">
                    <label>{{ trans('web::seat.item_type') }}</label>
                    <select id="typeName" class="form-control" name="filter[typeName][]" multiple>

                      @if($request->filter)
                        @foreach($request->filter as $name => $filter)

                          @if($name == 'typeName')
                            @foreach($filter as $detail)

                              <option value="{{ $detail }}" selected>{{ $detail }}</option>

                            @endforeach
                          @endif

                        @endforeach
                      @endif

                    </select>
                  </div>

                </div>
                <div class="col-md-6">

                  <div class="form-group">
                    <label>{{ trans('web::seat.client_name') }}</label>
                    <select id="clientName" class="form-control" name="filter[clientName][]" multiple>

                      @if($request->filter)
                        @foreach($request->filter as $name => $filter)
                          @if($name == 'clientName')
                            @foreach($filter as $detail)

                              <option value="{{ $detail }}" selected>{{ $detail }}</option>

                            @endforeach
                          @endif
                        @endforeach
                      @endif

                    </select>
                  </div>

                </div>

              </div>

            </div>
            <!-- /.box-body -->

            <div class="box-footer">

              <a href="{{ route('character.view.transactions', ['character_id' => $request->character_id]) }}"
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
          <h3 class="panel-title">{{ trans('web::seat.wallet_transactions') }}</h3>
        </div>
        <div class="panel-body">

          <table class="table datatable compact table-condensed table-hover table-responsive">
            <thead>
            <tr>
              <th>{{ trans('web::seat.date') }}</th>
              <th>{{ trans_choice('web::seat.type', 1) }}</th>
              <th>{{ trans('web::seat.qty') }}</th>
              <th>{{ trans('web::seat.price') }}</th>
              <th>{{ trans('web::seat.total') }}</th>
              <th>{{ trans('web::seat.client') }}</th>
            </tr>
            </thead>
            <tbody>

            @foreach($transactions as $transaction)

              <tr @if($transaction->transactionType == 'buy') class="danger" @endif>
                <td data-order="{{ $transaction->transactionDateTime }}">
                  <span data-toggle="tooltip"
                        title="" data-original-title="{{ $transaction->transactionDateTime }}">
                    {{ human_diff($transaction->transactionDateTime) }}
                  </span>
                </td>
                <td>
                  @if($transaction->transactionType == 'buy')
                    Bought
                  @else
                    Sold
                  @endif
                  {!! img('type', $transaction->typeID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
                  {{ $transaction->typeName }}
                  <i class="fa fa-home pull-right" data-toggle="tooltip"
                     title="" data-original-title="{{ $transaction->stationName }}"></i>
                </td>
                <td>
                  {{ $transaction->quantity }}
                </td>
                <td>{{ number($transaction->price) }}</td>
                <td>{{ number($transaction->price * $transaction->quantity) }}</td>
                <td>
                  {!! img('auto', $transaction->clientID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
                  {{ $transaction->clientName }}
                </td>
              </tr>

            @endforeach

            </tbody>
          </table>

        </div>
        @if($transactions->render())
          <div class="panel-footer">
            {!! $transactions->render() !!}
          </div>
        @endif
      </div>

    </div>
  </div>

@stop

@section('javascript')

  <script>
    $("#typeName").select2({
      ajax: {
        url: "{{ route('support.inv.types') }}",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            q: params.term, // search term
            page: params.page
          };
        },
      }
    });

    $("#clientName").select2({
      ajax: {
        url: "{{ route('support.char.tran.client_names', ['character_id' => $request->character_id]) }}",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            q: params.term, // search term
            page: params.page
          };
        },
      }
    });
  </script>

@stop
