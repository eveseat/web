<button data-toggle="modal" data-target="#fitting-detail" class="btn btn-sm btn-link"
   @if(isset($character_id))
   data-url="{{ route('character.view.fittings.items', ['character_id' => $character_id, 'fitting_id' => $fitting_id]) }}"
   @else
   data-url="{{ route('corporation.view.fittings.items', ['corporation_id' => $corporation_d, 'fitting_id' => $fitting_id]) }}"
   @endif
>
  <i class="fa fa-wrench"></i>
</button>