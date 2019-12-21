@if($row->permissions->contains('title', 'global.superuser'))
  <span class="badge badge-danger">{{ $row->permissions->count() }}</span>
@else
  <span class="badge badge-info">{{ $row->permissions->count() }}</span>
@endif