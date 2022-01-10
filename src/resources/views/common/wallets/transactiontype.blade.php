@include('web::partials.type', ['type_id' => $row->type_id, 'type_name' => $row->type->typeName, 'variation' => $row->type->group->categoryID == 9 ? 'bpc' : 'icon'])
<i class="fa fa-home float-right" data-bs-toggle="tooltip" title="{{ $row->locationName }}"></i>
