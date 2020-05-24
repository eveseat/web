<span data-toggle="tooltip" title="{{ $planet->type->typeName }}">
  {!! img('types', 'icon', $planet->type->typeID, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
</span>
{{ $planet->name }}