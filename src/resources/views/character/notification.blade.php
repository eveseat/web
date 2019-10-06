<i class="fa fa-comment" data-toggle="popover" data-placement="top" title="" data-html="true"
   data-trigger="hover" data-content="{{ clean_ccp_html($row->text) }}"></i>
{{ preg_replace('/([a-z0-9])([A-Z])/', '$1 $2', $row->type) }}