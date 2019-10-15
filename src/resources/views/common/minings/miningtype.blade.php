@if ( request()->has('all_linked_characters') && request('all_linked_characters') === "true")
  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ $character->name }}">
    {!! img('character', $character->character_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
  </span>
@endif

{!! img('type', $row->type_id, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
{{ $row->type->typeName }}