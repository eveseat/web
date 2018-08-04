@if(count($row->roles) > 0)
  <ul class="list-unstyled">
    @foreach($row->roles as $role)
      <li>{{ $role->title }}</li>
    @endforeach
  </ul>
@else
  <span class="label label-warning">No Roles</span>
@endif