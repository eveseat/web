{{ ucwords(str_replace('_', ' ', $row->ref_type)) }}

<span class="float-right">
  @if($row->description)
    <i class="fa fa-info-circle" data-toggle="tooltip" title="" data-original-title="{{ $row->description }}"></i>
  @endif

  @if(!is_null($row->reason))
    <i class="fa fa-comment" data-toggle="tooltip" title="" data-original-title="{{ $row->reason }}"></i>
  @endif
</span>