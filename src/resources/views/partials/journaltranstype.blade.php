{{ ucwords(str_replace('_', ' ', $row->ref_type)) }}
@if($row->description)
  <i class="fas fa-info-circle pull-right" data-toggle="tooltip"
     title="" data-original-title="{{ $row->description }}">
  </i>
@endif
@if(!is_null($row->reason))
  <i class="fas fa-comment pull-right" data-toggle="tooltip"
     title="" data-original-title="{{ $row->reason }}">
  </i>
@endif
