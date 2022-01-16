@if($corporation->titles()->where('id', $title->id)->whereHas('roles', function ($q) use ($role_name, $role_type) { $q->where('type', 'grantable_' . $role_type)->where('role', $role_name); })->exists())
    <input type="checkbox" disabled="disabled" checked="checked" class="form-check-input me-1 bg-success" />
@elseif($corporation->titles()->where('id', $title->id)->whereHas('roles', function ($q) use ($role_name, $role_type) { $q->where('type', $role_type)->where('role', $role_name); })->exists())
    <input type="checkbox" disabled="disabled" checked="checked" class="form-check-input me-1 bg-warning" />
@else
    <input type="checkbox" disabled="disabled" class="form-check-input me-1 bg-muted" />
@endif