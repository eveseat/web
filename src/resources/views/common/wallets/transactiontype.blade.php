@include('web::partials.type', ['type_id' => $row->type_id, 'type_name' => $row->type->typeName])
<i class="fa fa-home float-right" data-toggle="tooltip" title="{{ $row->locationName }}"></i>
