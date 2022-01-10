<i class="fa fa-comment" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true"
   title="{{ clean_ccp_html($row->getRawOriginal('text')) }}"></i>
{{ preg_replace('/([a-z0-9])([A-Z])/', '$1 $2', $row->type) }}