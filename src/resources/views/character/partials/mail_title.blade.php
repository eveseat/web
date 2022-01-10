<i class="fa fa-comment" data-bs-toggle="popover" data-bs-placement="top" title=""
   data-bs-trigger="hover" data-bs-content="{{ Str::limit(strip_tags(optional($row->body)->body), 200, '...') }}"></i>
{{ Str::limit($row->subject, 50, '...') }}
