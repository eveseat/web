{{ ucwords(str_replace('_', ' ', $row->ref_type)) }}
@if($row->description)
  <i class="fa fa-info-circle float-right" data-toggle="tooltip" title="" data-original-title="{{ $row->description }}"></i>
@endif
@if(!is_null($row->reason))
  <i class="fa fa-comment float-right" data-toggle="tooltip" title="" data-original-title="{{ $row->reason }}"></i>
@endif
