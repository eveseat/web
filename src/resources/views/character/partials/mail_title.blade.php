<i class="fa fa-comment" data-widget="popover" data-placement="top" title=""
   data-trigger="hover" data-content="{{ Str::limit(strip_tags(optional($row->body)->body), 200, '...') }}"></i>
{{ Str::limit($row->subject, 50, '...') }}
