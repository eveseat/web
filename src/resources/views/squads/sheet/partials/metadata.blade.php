<h3>Metadata</h3>

<ul class="list-group list-group-horizontal flex-fill text-center">
    <li class="list-group-item flex-fill border-0">
        <i class="fas @if($squad->is_moderated) fa-check text-success @else fa-times text-danger @endif"></i> Moderated
    </li>
    <li class="list-group-item flex-fill border-0">
        <i class="fas @if($squad->is_moderator) fa-check text-success @else fa-times text-danger @endif"></i> Moderator
    </li>
    <li class="list-group-item flex-fill border-0">
        <i class="fas @if($squad->is_member) fa-check text-success @else fa-times text-danger @endif"></i> Member
    </li>
    <li class="list-group-item flex-fill border-0">
        <i class="fas @if($squad->is_candidate) fa-check text-success @else fa-times text-danger @endif"></i> Candidate
    </li>
</ul>

<ul class="list-group list-group-horizontal flex-fill text-center">
    <li class="list-group-item flex-fill border-0">
        Type
        @switch($squad->type)
            @case('hidden')
                <span class="badge bg-dark">{{ ucfirst($squad->type) }}</span>
                @break
            @case('auto')
                <span class="badge bg-info">{{ ucfirst($squad->type) }}</span>
                @break
            @default
                <span class="badge bg-success">{{ ucfirst($squad->type) }}</span>
        @endswitch
    </li>
    <li class="list-group-item flex-fill border-0">
        @can('squads.show_members', $squad)
            Members <span class="badge badge-pill bg-secondary">{{ $squad->members_count }}</span>
        @endcan
    </li>
    <li class="list-group-item flex-fill border-0">
        Moderators <span class="badge badge-pill bg-warning">{{ $squad->moderators_count }}</span>
    </li>
    <li class="list-group-item flex-fill border-0">
        @can('squads.manage_candidates', $squad)
            @if($squad->type == 'manual')
                Candidates <span class="badge badge-pill bg-primary">{{ $squad->applications_count }}</span>
            @endif
        @endcan
    </li>
</ul>