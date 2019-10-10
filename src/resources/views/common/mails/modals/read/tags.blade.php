<span class="badge badge-warning">{{ $row->recipients->where('recipient_type', 'corporation')->count() }}</span>
<span class="badge badge-primary">{{ $row->recipients->where('recipient_type', 'alliance')->count() }}</span>
<span class="badge badge-info">{{ $row->recipients->where('recipient_type', 'character')->count() }}</span>
<span class="badge badge-success">{{ $row->recipients->where('recipient_type', 'mailing_list')->count() }}</span>