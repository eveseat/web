@if ( request()->has('all_linked_characters') && request('all_linked_characters') === "true")
  <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $character->name }}">
    {!! img('characters', 'portrait', $character->character_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
  </span>
@endif

@include('web::partials.type', ['type_id' => $row->type_id, 'type_name' => $row->type->typeName])