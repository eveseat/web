@if($row->recipients->where('recipient_type', 'alliance')->count() > 0)
  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Alliances">
    <span class="badge badge-primary">{{ $row->recipients->where('recipient_type', 'alliance')->count() }}</span>
  </span>
@endif

@if($row->recipients->where('recipient_type', 'corporation')->count() > 0)
  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Corporations">
    <span class="badge badge-warning">{{ $row->recipients->where('recipient_type', 'corporation')->count() }}</span>
  </span>
@endif

@if($row->recipients->where('recipient_type', 'character')->count() > 0)
  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Characters">
    <span class="badge badge-info">{{ $row->recipients->where('recipient_type', 'character')->count() }}</span>
  </span>
@endif

@if($row->recipients->where('recipient_type', 'mailing_list')->count() > 0)
  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Mailing Lists">
    <span class="badge badge-success">{{ $row->recipients->where('recipient_type', 'mailing_list')->count() }}</span>
  </span>
@endif
