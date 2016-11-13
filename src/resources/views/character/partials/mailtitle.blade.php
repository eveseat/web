<i class="fa fa-comment" data-toggle="popover" data-placement="top" title=""
   data-trigger="hover" data-content="{{ str_limit(strip_tags($row->body), 200, '...') }}"></i>
{{ str_limit($row->title, 50, '...') }}
