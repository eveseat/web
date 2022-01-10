{{ trans(sprintf('web::wallet.%s', $row->ref_type)) }}

<span class="float-right">
  @if(! empty($row->reason))
    <i class="fa fa-comment" data-bs-toggle="tooltip" title="{{ $row->reason }}"></i>
  @endif
  @if($row->description)
    <i class="fa fa-info-circle" data-bs-toggle="tooltip" title="{{ $row->description }}"></i>
  @endif
</span>