@switch($row->type)
  @case('manual')
    <span class="badge bg-success">{{ ucfirst($row->type) }}</span>
    @break
  @case('auto')
    <span class="badge bg-info">{{ ucfirst($row->type) }}</span>
    @break
  @case('hidden')
    <span class="badge bg-gray-dark">{{ ucfirst($row->type) }}</span>
    @break
  @default
    {{ ucfirst($row->type) }}
@endswitch