<div class="panel panel-default">
    <div class="panel-body">
        <div class="form-group container">
            <div class="row">
                <div class="col-sm py-2">
                    <label for="region-selector">Region: </label>
                    <select class="form-control" id="region-selector">
                        <option class="dropdown-item" value="">Any</option>
                        @foreach ($regions as $region)
                            <option class="dropdown-item" value="{{$region->itemID}}">{{$region->itemName}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm py-2">
                    <label for="constellation-selector">Constellation: </label>
                    <select class="form-control" id="constellation-selector">
                        <option class="dropdown-item" value="">Any</option>
                        @foreach ($constellations as $constellation)
                            <option class="dropdown-item" value="{{$constellation->itemID}}">{{$constellation->itemName}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm py-2">
                    <label for="system-selector">System: </label>
                        <select class="form-control" id="system-selector">
                            <option class="dropdown-item" value="">Any</option>
                            @foreach ($systems as $system)
                                <option class="dropdown-item" value="{{$system->itemID}}">{{$system->itemName}}</option>
                            @endforeach
                        </select>
                </div> 
            </div>
            <div class="row">
                <div class="col-sm py-2">
                    <label for="moon-content-selector">Moon Contents: </label>
                    <div class="input-group">
                        <select class="form-control select2" name="moon-content-selector[]"id="moon-content-selector" multiple="multiple">
                            @foreach ($moonContents as $item)
                                <option value="{{$item}}">{{ucfirst($item)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <input type="button" class="btn btn-primary" id="filterSubmit" value="Search"/>
        </div>
    </div>
</div>
@push('javascript')
<script>
$('#region-selector').select2({
    minimumInputLength : 3,
})
$('#constellation-selector').select2({
    minimumInputLength : 3,
})
$('#system-selector').select2({
    minimumInputLength : 3,
})

$('#moon-content-selector[multiple]').select2({
    closeOnSelect : false,
    multiple : true,
})

$('#filterSubmit').click(function () {
    window.LaravelDataTables['dataTableBuilder'].ajax.reload();
})

</script>
@endpush