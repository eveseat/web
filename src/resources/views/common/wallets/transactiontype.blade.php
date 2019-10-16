{!! img('type', $row->type_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
{{ $row->type->typeName }}
<i class="fa fa-home float-right" data-toggle="tooltip" title="{{ $row->locationName }}"></i>
