<div class="panel panel-default">
    <div class="panel-body">
        <div class="form-group container">
            <div class="row">
                <div class="col-sm py-2">
                    <label for="dt-filters-region">{{ trans_choice('web::moons.region', 1) }}</label>
                    <select class="form-control" id="dt-filters-region"></select>
                </div>
                <div class="col-sm py-2">
                    <label for="dt-filters-constellation">{{ trans_choice('web::moons.constellation', 1) }}</label>
                    <select class="form-control" id="dt-filters-constellation"></select>
                </div>
                <div class="col-sm py-2">
                    <label for="dt-filters-system">{{ trans_choice('web::moons.system', 1) }}</label>
                    <select class="form-control" id="dt-filters-system"></select>
                </div> 
            </div>
            <div class="row">
                <div class="col-sm py-2">
                    <label for="dt-filters-rank">{{ trans('web::moons.moon_rank') }}</label>
                    <div class="input-group">
                        <select class="form-control" name="dt-filters-rank[]" id="dt-filters-rank" multiple="multiple">
                            @foreach ($groups as $group_id => $group)
                                <option value="{{ $group_id }}">{{ $group }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm py-2">
                    <label for="dt-filters-product">{{ trans('web::moons.producing') }}</label>
                    <select class="form-control" name="dt-filters-product[]" id="dt-filters-product" multiple="multiple"></select>
                </div>
            </div>
            <button type="button" class="btn btn-primary" id="dt-filters-search">
                <i class="fas fa-search"></i>
                {{ trans('web::seat.search') }}
            </button>
        </div>
    </div>
</div>
@push('javascript')
<script>
$('#dt-filters-region').select2({
    allowClear: true,
    placeholder: '{{ trans('web::moons.filter_by_region') }}',
    ajax: {
        url: '{{ route('seatcore::fastlookup.regions') }}',
        dataType: 'json'
    }
});

$('#dt-filters-constellation').select2({
    allowClear: true,
    placeholder: '{{ trans('web::moons.filter_by_constellation') }}',
    ajax: {
        url: '{{ route('seatcore::fastlookup.constellations') }}',
        dataType: 'json',
        data: function (params) {
            var region_selector = $('#dt-filters-region');

            return {
                term: params.term,
                q: params.term,
                _type: params._type,
                region_filter: region_selector.val() === null ? 0 : region_selector.val()
            }
        }
    }
});

$('#dt-filters-system').select2({
    allowClear: true,
    placeholder: '{{ trans('web::moons.filter_by_system') }}',
    ajax: {
        url: '{{ route('seatcore::fastlookup.systems') }}',
        dataType: 'json',
        data: function (params) {
            var region_selector = $('#dt-filters-region');
            var constellation_selector = $('#dt-filters-constellation');

            return {
                term: params.term,
                q: params.term,
                _type: params._type,
                region_filter: region_selector.val() === null ? 0 : region_selector.val(),
                constellation_filter: constellation_selector.val() === null ? 0 : constellation_selector.val()
            }
        }
    }
});

$('#dt-filters-rank').select2({
    closeOnSelect: false,
    multiple: true,
    templateResult: formatSelectRank,
    templateSelection: formatSelectRank
});

$('#dt-filters-product').select2({
    closeOnSelect: false,
    multiple: true,
    ajax: {
        url: '{{ route('seatcore::fastlookup.items') }}',
        dataType: 'json',
        data: function (params) {
            return {
                term: params.term,
                q: params.term,
                _type: params._type,
                market_filters: [
                    501, // Raw Moon Materials market group
                    1857 // Minerals market group
                ]
            }
        }
    },
    templateResult: formatSelectType,
    templateSelection: formatSelectType
});

$('#dt-filters-search').click(function () {
    window.LaravelDataTables['dataTableBuilder'].ajax.reload();
});

function formatSelectRank(rank) {
    if (! rank.id) {
        return rank.text;
    }

    switch (rank.id) {
        case 2396: // Ubiquitous
        case '2396':
            return $(`<span><i class="fas fa-square text-success"></i> ${rank.text}</span>`);
        case 2397: // Common
        case '2397':
            return $(`<span><i class="fas fa-square text-primary"></i> ${rank.text}</span>`);
        case 2398: // Uncommon
        case '2398':
            return $(`<span><i class="fas fa-square text-info"></i> ${rank.text}</span>`);
        case 2400: // Rare
        case '2400':
            return $(`<span><i class="fas fa-square text-warning"></i> ${rank.text}</span>`);
        case 2401: // Exceptional
        case '2401':
            return $(`<span><i class="fas fa-square text-danger"></i> ${rank.text}</span>`);
        default:
            return rank.text;
    }
}

function formatSelectType(type) {
    if (! type.id) {
        return type.text;
    }

    return $(`<span>${type.img} ${type.text}</span>`);
}
</script>
@endpush