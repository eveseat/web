<i class="fa fa-comment" data-toggle="tooltip" data-placement="top" data-html="true"
   title="{{ clean_ccp_html($row->getRawOriginal('text')) }}"></i>
{{ preg_replace('/([a-z0-9])([A-Z])/', '$1 $2', $row->type) }}