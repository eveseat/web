@if($row->character_id != request()->route()->parameter('character_id'))
  @include('web::partials.character-icon-hover', ['character' => $row->character])
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