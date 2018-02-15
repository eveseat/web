@if($row->recipients->where('recipient_type', 'alliance')->count() > 0)
  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Alliances">
    <span class="label label-primary">{{ $row->recipients->where('recipient_type', 'alliance')->count() }}</span>
  </span>
@endif

@if($row->recipients->where('recipient_type', 'corporation')->count() > 0)
  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Corporations">
    <span class="label label-warning">{{ $row->recipients->where('recipient_type', 'corporation')->count() }}</span>
  </span>
@endif

@if($row->recipients->where('recipient_type', 'character')->count() > 0)
  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Characters">
    <span class="label label-info">{{ $row->recipients->where('recipient_type', 'character')->count() }}</span>
  </span>
@endif

@if($row->recipients->where('recipient_type', 'mailing_list')->count() > 0)
  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Mailing Lists">
    <span class="label label-success">{{ $row->recipients->where('recipient_type', 'mailing_list')->count() }}</span>
  </span>
@endif
