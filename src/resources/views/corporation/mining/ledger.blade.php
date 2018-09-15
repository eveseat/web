@extends('web::corporation.mining.layouts.view', ['sub_viewname' => 'ledger'])

@section('title', trans_choice('web::seat.corporation', 1) . ' | ' . trans('web::seat.mining') . ' ' . trans_choice('web::seat.mining_ledger', 2))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.mining') . ' ' . trans_choice('web::seat.mining_ledger', 2))

@section('mining_content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ trans_choice('web::seat.available_ledger', $ledgers->count()) }}</h3>
        </div>
        <div class="panel-body">
            @foreach($ledgers->chunk(12) as $chunk)
            <div class="row">
                @foreach ($chunk as $ledger)
                <div class="col-xs-1">
                    <span class="text-bold">
                        <a href="{{ route('corporation.view.mining_ledger', [
                            request()->route()->parameter('corporation_id'),
                            date('Y', strtotime($ledger->year. '-01-01')),
                            date('m', strtotime($ledger->year . '-' . $ledger->month . '-01'))
                        ]) }}">{{ date('M Y', strtotime($ledger->year . "-" . $ledger->month . "-01")) }}</a>
                    </span>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ trans_choice('web::seat.mining_ledger', 1) }}</h3>
        </div>
        <div class="panel-body">
            <table class="table compact table-condensed table-hover table-responsive" id="corporation-mining-ledger">
                <thead>
                    <tr>
                        <th>{{ trans_choice('web::seat.name', 1) }}</th>
                        <th>{{trans('web::seat.group_main') }}</th>
                        <th>{{ trans('web::seat.quantity') }}</th>
                        <th>{{ trans('web::seat.volume') }}</th>
                        <th>{{ trans('web::seat.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $character_id => $entry)
                    <tr>
                        <td data-order="{{ $character_id }}">
                            {!! img('character', $character_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                            <a href="{{ route('character.view.sheet', ['character_id' => $character_id]) }}">
                                <span class="id-to-name" data-id="{{ $character_id }}">Unknown</span>
                            </a>
                        </td>
                        <td>
                            <span class="character-id-to-main-character"
                                  data-character-id="{{$character_id}}">Unknown</span>
                        </td>
                        <td class="text-right" data-order="{{ $entry->quantity }}">{{ number_format($entry->quantity, 2) }}</td>
                        <td class="text-right" data-order="{{ $entry->volumes }}">{{ number_format($entry->volumes, 2) }} m3</td>
                        <td class="text-right" data-order="{{ $entry->amount }}">{{ number_format($entry->amount, 2) }} ISK</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="panel-footer">Total : {{ number_format($entries->sum('amount'), 2) }}</div>
    </div>
@stop

@push('javascript')
<script type="text/javascript">
    $(function(){
        $('#corporation-mining-ledger').dataTable({
            'order': [
                [3, 'desc']
            ],
            'paging': false
        });
    });
</script>
@include('web::includes.javascript.character-id-to-main-character')
@endpush
