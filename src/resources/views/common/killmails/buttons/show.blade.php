<button data-toggle="modal" data-target="#killmail-detail"
        @if(request()->character)
        data-url="{{ route('seatcore::character.view.killmail', ['character' => request()->character, 'killmail' => $row]) }}"
        @endif
        @if(request()->corporation)
        data-url="{{ route('seatcore::corporation.view.killmail', ['corporation' => request()->corporation, 'killmail' => $row]) }}"
        @endif
        class="btn btn-sm btn-link">
    <i class="fas fa-eye"></i> Show
</button>