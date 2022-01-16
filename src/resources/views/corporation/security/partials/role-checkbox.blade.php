@if($title->roles->where('type', 'grantable_' . $role_type)->where('role', $role_name)->isNotEmpty())
    <input type="checkbox" disabled="disabled" checked="checked" class="form-check-input me-1 bg-success" />
@elseif($title->roles->where('type', $role_type)->where('role', $role_name)->isNotEmpty())
    <input type="checkbox" disabled="disabled" checked="checked" class="form-check-input me-1 bg-warning" />
@else
    <input type="checkbox" disabled="disabled" class="form-check-input me-1 bg-muted" />
@endif