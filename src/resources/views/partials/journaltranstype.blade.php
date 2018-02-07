{{ ucwords(str_replace('_', ' ', $row->ref_type)) }}
@if($row->argName1)
  <i class="fa fa-info-circle pull-right" data-toggle="tooltip"
     title="" data-original-title="{{ $row->argName1 }}">
  </i>
@endif
