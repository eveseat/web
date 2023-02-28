@extends('web::layouts.grids.12')

@section('title', trans('web::seat.moons_reporter'))
@section('page_header', trans('web::seat.tools'))
@section('page_description', trans('web::seat.moons_reporter'))

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Market Browser</h4>
        </div>
        <div class="card-body">
            <div class="form-group">
                <select id="dt-item-selector" class="form-control" style="width: 100%;">
                    <option value="{{ $default_item->typeID }}" selected>{{ $default_item->typeName }}</option>
                </select>
            </div>

            <div class="form-group">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-secondary active">
                        <input type="radio" name="options" id="sellOrdersRadio" checked> Sell
                    </label>
                    <label class="btn btn-secondary">
                        <input type="radio" name="options" id="buyOrdersRadio"> Buy
                    </label>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title" id="order-card-header">{{ $default_item->typeName }}</h4>
        </div>
        <div class="card-body">
            {!! $dataTable->table(['class' => 'table table-hover']) !!}
        </div>
    </div>
@endsection

@push('javascript')
    {!! $dataTable->scripts() !!}
    <script>
        $(document).ready(function () {
            function settingsChanged() {
                window.LaravelDataTables['dataTableBuilder'].ajax.reload();
                const text = $("#dt-item-selector").select2("data")[0].text;
                $('#order-card-header').text(text)
            }

            $('#dt-item-selector')
                .select2({
                    ajax: {
                        url: "{{ route("fastlookup.items") }}",
                        dataType: 'json'
                    },
                })
                .on('change', settingsChanged);

            $('#sellOrdersRadio').on('change', settingsChanged);
            $('#buyOrdersRadio').on('change', settingsChanged);
        });
    </script>
@endpush

