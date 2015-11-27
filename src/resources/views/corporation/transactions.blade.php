@extends('web::corporation.layouts.view', ['viewname' => 'transactions'])

@section('title', ucfirst(trans_choice('web::corporation.corporation', 1)) . ' Transactions')
@section('page_header', ucfirst(trans_choice('web::corporation.corporation', 1)) . ' Transactions')

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="row">
    <div class="col-md-12">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Filters</h3>
        </div>
        <div class="panel-body">

          <form role="form" action="{{ route('corporation.view.transactions', ['corporation_id' => $request->corporation_id]) }}"
                method="get">

            <div class="box-body">

              <div class="row">
                <div class="col-md-6">

                  <div class="form-group">
                    <label>Item Type</label>
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
                    <label>Client Name</label>
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

              <a href="{{ route('corporation.view.transactions', ['corporation_id' => $request->corporation_id]) }}"
                 class="btn btn-warning pull-left">
                {{ trans('web::general.clear_filters') }}
              </a>

              <button type="submit" class="btn btn-primary pull-right">
                {{ ucfirst(trans_choice('web::general.filter', 1)) }}
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
          <h3 class="panel-title">Wallet Transactions</h3>
        </div>
        <div class="panel-body">

          <table class="table table-condensed table-hover">
            <tbody>
            <tr>
              <th>Date</th>
              <th>Type</th>
              <th>#</th>
              <th>Price</th>
              <th>Total</th>
              <th>Client</th>
            </tr>

            @foreach($transactions as $transaction)

              <tr @if($transaction->transactionType == 'buy') class="danger" @endif>
                <td>{{ human_diff($transaction->transactionDateTime) }}</td>
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
                <td>{{ $transaction->price }}</td>
                <td>{{ $transaction->price * $transaction->quantity }}</td>
                <td>
                  {!! img('auto', $transaction->clientID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
                  {{ $transaction->clientName }}
                </td>
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
        url: "{{ route('support.corp.tran.client_names', ['corporation_id' => $request->corporation_id]) }}",
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
