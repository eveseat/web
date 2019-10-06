@extends('web::corporation.mining.layouts.view', ['sub_viewname' => 'ledger', 'breadcrumb' => trans('web::seat.mining')])

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
            {{ $dataTable->table() }}
        </div>
    </div>
@stop

@push('javascript')

    {!! $dataTable->scripts() !!}

    @include('web::includes.javascript.character-id-to-main-character')

@endpush
