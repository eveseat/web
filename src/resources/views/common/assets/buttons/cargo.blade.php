@if(is_a($row, \Seat\Eveapi\Models\Assets\CharacterAsset::class))
    <button class="btn btn-sm btn-link" data-dismiss="modal" data-toggle="modal" data-target="#container-detail" data-url="{{ route('character.view.assets.container', ['character_id' => $row->character_id, 'item_id' => $row->item_id]) }}">
        <i class="fas fa-cubes"></i>
    </button>
@endif
@if(is_a($row, \Seat\Eveapi\Models\Assets\CorporationAsset::class))
    <button class="btn btn-sm btn-link" data-dismiss="modal" data-toggle="modal" data-target="#container-detail" data-url="{{ route('corporation.view.assets.container', ['corporation_id' => $row->corporation_id, 'item_id' => $row->item_id]) }}">
        <i class="fas fa-cubes"></i>
    </button>
@endif