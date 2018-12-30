{!! img('type', $row->type_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
{{ $row->type->typeName }}
<i class="fas fa-home pull-right" data-toggle="tooltip" title="{{ $row->locationName }}"></i>
