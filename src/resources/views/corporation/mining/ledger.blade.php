@extends('web::corporation.mining.layouts.view', ['sub_viewname' => 'ledger', 'breadcrumb' => trans('web::seat.mining')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.mining') . ' ' . trans_choice('web::seat.mining_ledger', 2))

@section('mining_content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ trans_choice('web::seat.available_ledger', $ledgers->count()) }}</h3>
        </div>
        <div class="card-body p-2">
            @foreach($ledgers->chunk(12) as $chunk)
            <ul class="nav nav-pills justify-content-between">
                @foreach ($chunk as $ledger)
                    <li class="nav-item">
                        @if(date('Y', strtotime($ledger->year . '-01-01')) == (request()->route()->parameter('year') ?: carbon()->isoFormat('YYYY')) && date('m', strtotime($ledger->year . '-' . $ledger->month . '-01')) == (request()->route()->parameter('month') ?: carbon()->isoFormat('MM')))
                            <a href="{{ route('seatcore::corporation.view.mining_ledger', [
                                $corporation,
                                date('Y', strtotime($ledger->year. '-01-01')),
                                date('m', strtotime($ledger->year . '-' . $ledger->month . '-01'))
                            ]) }}" class="nav-link active">{{ date('M Y', strtotime($ledger->year . "-" . $ledger->month . "-01")) }}</a>
                        @else
                            <a href="{{ route('seatcore::corporation.view.mining_ledger', [
                                $corporation,
                                date('Y', strtotime($ledger->year. '-01-01')),
                                date('m', strtotime($ledger->year . '-' . $ledger->month . '-01'))
                            ]) }}" class="nav-link">{{ date('M Y', strtotime($ledger->year . "-" . $ledger->month . "-01")) }}</a>
                        @endif
                    </li>
                @endforeach
            </ul>
            @endforeach
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ trans_choice('web::seat.mining_ledger', 1) }}</h3>
        </div>
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>

    @include('web::common.minings.modals.details.details')
@stop

@push('javascript')

    {!! $dataTable->scripts() !!}

    @include('web::includes.javascript.character-id-to-main-character')

    <script>
        $(document).ready(function() {
            $('#mining-detail').on('show.bs.modal', function (e) {
                var body = $(e.target).find('.modal-body');
                body.html('Loading...');

                $.ajax($(e.relatedTarget).data('url'))
                    .done(function (data) {
                        body.html(data);
                        $("[data-toggle=tooltip]").tooltip();
                    });
            });
        });
    </script>

@endpush
