<button type="button" data-bs-toggle="modal" data-bs-target="#scopesModal" class="btn btn-link btn-sm"
        data-url="{{ route('seatcore::profile.character.scopes', ['user_id' => $user->id, 'character_id' => $character->character_id]) }}">
  <i class="fas fa-shield-alt"></i>
  {{ trans_choice('web::seat.scope', 2) }}
</button>
