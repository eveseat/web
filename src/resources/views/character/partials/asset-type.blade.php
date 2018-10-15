{!! img('type', $row->type_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
@if($row->type)
  @if($row->name != $row->type->typeName)
    {{ $row->name }} ({{ $row->type->typeName }})
  @else
    {{ $row->type->typeName }}
  @endif
@else
  Unknown
@endif
@if(! $row->is_singleton)
  <span class="text-red">(packaged)</span>
@endif