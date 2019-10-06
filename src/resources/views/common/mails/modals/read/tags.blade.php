<span class="label label-warning">{{ $row->recipients->where('recipient_type', 'corporation')->count() }}</span>
<span class="label label-primary">{{ $row->recipients->where('recipient_type', 'alliance')->count() }}</span>
<span class="label label-info">{{ $row->recipients->where('recipient_type', 'character')->count() }}</span>
<span class="label label-success">{{ $row->recipients->where('recipient_type', 'mailing_list')->count() }}</span>