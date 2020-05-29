@if($row->isAdmin())
  <i class="fas fa-check text-success"></i>
@else
  <i class="fas fa-times text-danger"></i>
@endif