@if(request('all_linked_characters') === "true")
  {!! img('characters', 'portrait', $row->character_id, 32, ['class' => 'img-circle eve-icon small-icon'],false) !!}
@endif

@if($row->type)
  @if($row->name != $row->type->typeName)
    @include('web::partials.type', ['type_id' => $row->type_id, 'type_name' => sprintf('%s (%s)', $row->name, $row->type->typeName)])
  @else
    @include('web::partials.type', ['type_id' => $row->type_id, 'type_name' => $row->type->typeName])
  @endif
@else
  @include('web::partials.type', ['type_id' => $row->type_id, 'type_name' => trans('web::seat.unknown')])
@endif
@if(! $row->is_singleton)
  <span class="text-red">(packaged)</span>
@endif