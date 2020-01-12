@if($entity->character && $entity->character->entity_id != 0)
  @include('web::partials.character', ['character' => $entity->character])
  <br />
@endif
@if($entity->corporation && $entity->corporation->entity_id != 0)
  @include('web::partials.corporation', ['corporation' => $entity->corporation])
@endif
@if($entity->alliance && $entity->alliance->entity_id != 0)
  @include('web::partials.alliance', ['alliance' => $entity->alliance])
@endif
@if(
    ($entity->corporation && $entity->corporation->entity_id != 0) ||
    ($entity->alliance && $entity->alliance->entity_id != 0)
   )
  <br/>
@endif
@if($entity->faction && $entity->faction->entity_id != 0)
  @include('web::partials.faction', ['faction' => $entity->faction])
@endif