<h3 class="display-6 mt-5 mb-3" id="title-{{ $title->title_id }}-container-access-{{ $role_type }}">{{ $section_label }}</h3>

<ul class="list-inline d-flex justify-content-between mb-4">
    @foreach(['Container_Take_1', 'Container_Take_2', 'Container_Take_3', 'Container_Take_4', 'Container_Take_5', 'Container_Take_6', 'Container_Take_7'] as $role)
        <li class="list-inline-item">
            <span @class([
                'avatar',
                'bg-success' => $title->roles->where('type', 'grantable_roles_at_' . $role_type)->where('role', $role)->isNotEmpty(),
                'bg-warning' => $title->roles->where('type', 'grantable_roles_at_' . $role_type)->where('role', $role)->isEmpty() && $title->roles->where('type', 'roles_at_' . $role_type)->where('role', $role)->isNotEmpty(),
                'bg-muted' => $title->roles->where('role', $role)->isEmpty(),
            ])></span>
        </li>
    @endforeach
</ul>

@for($i = 1; $i <= 4; $i++)
    <div class="row row-cols-2 ps-3 pe-3 mt-3">
        <div class="col ps-3 pe-3">
            <div class="font-weight-bold mb-2">
                @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Container_Take_' . $i, 'role_type' => 'roles_at_' . $role_type])
                {{ $corporation->divisions->where('type', 'hangar')->where('division', $i)->first()->name }}
            </div>
        </div>
        @if($i + 4 < 8)
            <div class="col ps-3 pe-3">
                <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Container_Take_' . $i + 4, 'role_type' => 'roles_at_' . $role_type])
                    {{ $corporation->divisions->where('type', 'hangar')->where('division', $i + 4)->first()->name }}
                </div>
            </div>
        @endif
    </div>
@endfor