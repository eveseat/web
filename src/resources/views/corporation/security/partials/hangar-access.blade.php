<h3 class="display-6 mt-5 mb-3" id="title-{{ $title->title_id }}-hangar-access-{{ $role_type }}">{{ $section_label }}</h3>

<ul class="list-inline d-flex justify-content-between mb-4">
    @foreach(['Hangar_Query_1', 'Hangar_Take_1', 'Hangar_Query_2', 'Hangar_Take_2', 'Hangar_Query_3', 'Hangar_Take_3', 'Hangar_Query_4', 'Hangar_Take_4', 'Hangar_Query_5', 'Hangar_Take_5', 'Hangar_Query_6', 'Hangar_Take_6', 'Hangar_Query_7', 'Hangar_Take_7'] as $role)
        <li class="list-inline-item">
            <span @class([
                'avatar',
                'bg-success' => $corporation->titles()->where('id', $title->id)->whereHas('roles', function ($q) use ($role, $role_type) { $q->where('type', 'grantable_roles_at_' . $role_type)->where('role', $role); })->exists(),
                'bg-warning' => ! $corporation->titles()->where('id', $title->id)->whereHas('roles', function ($q) use ($role, $role_type) { $q->where('type', 'grantable_roles_at_' . $role_type)->where('role', $role); })->exists() && $corporation->titles()->where('id', $title->id)->whereHas('roles', function ($q) use ($role, $role_type) { $q->where('type', 'roles_at_' . $role_type)->where('role', $role); })->exists(),
                'bg-muted' => ! $corporation->titles()->where('id', $title->id)->whereHas('roles', function ($q) use ($role) { $q->where('role', $role); })->exists(),
            ])></span>
        </li>
    @endforeach
</ul>

@for($i = 1; $i <= 7; $i++)
    <div class="row row-cols-2 ps-3 pe-3 mt-3">
        <div class="col ps-3 pe-3">
            <div class="font-weight-bold mb-2">
                @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Hangar_Query_' . $i, 'role_type' => 'roles_at_' . $role_type])
                {{ $corporation->divisions()->where('type', 'wallet')->where('division', $i)->first()->name }} (Query)
            </div>
        </div>
        <div class="col ps-3 pe-3">
            <div class="font-weight-bold mb-2">
                @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Hangar_Take_' . $i, 'role_type' => 'roles_at_' . $role_type])
                {{ $corporation->divisions()->where('type', 'wallet')->where('division', $i)->first()->name }} (Take)
            </div>
        </div>
    </div>
@endfor