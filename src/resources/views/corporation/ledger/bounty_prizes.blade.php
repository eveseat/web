@extends('web::layouts.corporation', ['viewname' => 'bounty_prizes', 'breadcrumb' => trans_choice('web::seat.bounty_prizes', 2)])

@section('page_description', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.bounty_prizes', 2))

@section('corporation_content')

    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="col-auto me-5">
                <h3 class="card-title">{{ trans_choice('web::seat.available_ledger', $periods->count()) }}</h3>
            </div>
        </div>
        <div class="card-body">

            @foreach ($periods->chunk(12) as $chunk)
                <ul class="nav nav-pills justify-content-between">

                    @foreach ($chunk as $period)
                        <li class="nav-item">
                            @if(date('Y', strtotime($period->year . '-01-01')) == (request()->route()->parameter('year') ?: carbon()->isoFormat('YYYY')) && date('m', strtotime($period->year . '-' . $period->month . '-01')) == (request()->route()->parameter('month') ?: carbon()->isoFormat('MM')))
                                <a href="{{ route('seatcore::corporation.view.ledger.bounty_prizes', [
                    $corporation,
                    date('Y', strtotime($period->year. '-01-01')),
                    date('m', strtotime($period->year . '-' . $period->month . '-01'))
                  ]) }}" class="nav-link active">{{ date("M Y", strtotime(sprintf('%d-%d-01', $period->year, $period->month))) }}</a>
                            @else
                                <a href="{{ route('seatcore::corporation.view.ledger.bounty_prizes', [
                    $corporation,
                    date('Y', strtotime($period->year. '-01-01')),
                    date('m', strtotime($period->year . '-' . $period->month . '-01'))
                ]) }}" class="nav-link">{{ date("M Y", strtotime(sprintf('%d-%d-01', $period->year, $period->month))) }}</a>
                            @endif
                        </li>
                    @endforeach

                </ul>
            @endforeach
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="col-auto me-5">
                <h3 class="card-title">{{ trans_choice('web::seat.bounty_prizes', 2) }} - {{ date("M Y", strtotime(sprintf('%d-%d-01', $year, $month))) }}</h3>
            </div>
        </div>

        <table class="table datatable card-table table-vcenter table-hover table-striped text-nowrap">
            <thead>
            <tr>
                <th>{{ trans_choice('web::seat.name', 1) }}</th>
                <th>{{ trans_choice('web::seat.total', 1) }}</th>
            </tr>
            </thead>
            <tbody>

            @foreach ($entries as $entry)
                <tr>
                    <td data-order="{{ $entry->second_party->name }}">
                        @switch($entry->second_party->category)
                            @case('character')
                            @include('web::partials.character', ['character' => $entry->second_party])
                            @break
                            @case('corporation')
                            @include('web::partials.corporation', ['corporation' => $entry->second_party])
                            @break
                            @case('alliance')
                            @include('web::partials.alliance', ['alliance' => $entry->second_party])
                            @break
                        @endswitch
                    </td>
                    <td data-order="{{ $entry->total }}">{{ number_format($entry->total) }}</td>
                </tr>
            @endforeach

            </tbody>
        </table>

        <div class="card-footer">
            <i>Total: {{ number_format($entries->sum('total')) }}</i>
        </div>
    </div>

@stop

@push('javascript')
    <script>
        ids_to_names();
    </script>
@endpush
