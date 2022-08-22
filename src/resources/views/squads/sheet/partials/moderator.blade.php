<div class="col-2">
    <div class="row g-3 align-items-center">
        <a href="{{ route('seatcore::character.view.default', ['character' => $moderator->main_character->character_id]) }}" class="col-auto">
            {!! img('characters', 'portrait', $moderator->main_character->character_id, 32, ['class' => 'avatar'], false) !!}
        </a>
        <div class="col text-truncate">
            <a href="{{ route('seatcore::character.view.default', ['character' => $moderator->main_character->character_id]) }}" class="text-reset d-block text-truncate">{{ $moderator->main_character->name }}</a>
            @can('squads.manage_moderators', $squad)
                <form method="post" action="{{ route('seatcore::squads.moderators.destroy', ['squad' => $squad, 'user' => $moderator]) }}">
                    {!! csrf_field() !!}
                    {!! method_field('DELETE') !!}
                    <button type="submit" data-seat-entity="moderator" class="btn btn-sm btn-danger d-sm-inline-block confirmdelete">
                        <i class="fas fa-trash-alt"></i>
                        Remove
                    </button>
                </form>
            @endcan
        </div>
    </div>
</div>