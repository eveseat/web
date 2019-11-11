<span data-toggle="tooltip"
      title="" data-original-title="{{ $row->stationName }}">
  <i class="fas fa-map-marker-alt"></i>
</span>
@if (request('all_linked_characters') === "true")
  {!! img('characters', 'portrait', $row->character_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
@endif
@include('web::partials.type', ['type_id' => $row->type_id, 'type_name' => $row->typeName])
