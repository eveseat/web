@if(is_a($row, \Seat\Eveapi\Models\Assets\CharacterAsset::class))
    <button class="btn btn-sm btn-link" data-dismiss="modal" data-toggle="modal" data-target="#fitting-detail" data-url="{{ route('seatcore::character.view.assets.fitting', ['character' => $row->character, 'item_id' => $row->item_id]) }}">
        <i class="fas fa-wrench"></i>
    </button>
@endif
@if(is_a($row, \Seat\Eveapi\Models\Assets\CorporationAsset::class))
    <button class="btn btn-sm btn-link" data-dismiss="modal" data-toggle="modal" data-target="#fitting-detail" data-url="{{ route('seatcore::corporation.view.assets.fitting', ['corporation' => $row->corporation, 'item_id' => $row->item_id]) }}">
        <i class="fas fa-wrench"></i>
    </button>
@endif