<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">{{ trans_choice('web::seat.attribute', 2) }}</h3>
    </div>
    <div class="card-body">

        <div class="row">

            <div class="col-md-6">
                <dl class="row">
                    <dt class="col-10">Perception</dt>
                    <dd class="col-2">
                        <span class="badge bg-success">{{ $character->pilot_attributes->perception ?: 0 }}</span>
                    </dd>

                    <dt class="col-10">Memory</dt>
                    <dd class="col-2">
                        <span class="badge bg-purple">{{ $character->pilot_attributes->memory ?: 0 }}</span>
                    </dd>

                    <dt class="col-10">Willpower</dt>
                    <dd class="col-2">
                        <span class="badge bg-danger">{{ $character->pilot_attributes->willpower ?: 0 }}</span>
                    </dd>

                    <dt class="col-10">Intelligence</dt>
                    <dd class="col-2">
                        <span class="badge bg-info">{{ $character->pilot_attributes->intelligence ?: 0 }}</span>
                    </dd>

                    <dt class="col-10">Charisma</dt>
                    <dd class="col-2">
                        <span class="badge bg-yellow">{{ $character->pilot_attributes->charisma ?: 0 }}</span>
                    </dd>
                </dl>
            </div>

            <div class="col-md-6">
                <dl>
                    <dt>{{ trans('web::seat.bonus_remaps') }}</dt>
                    <dd>{{ $character->pilot_attributes->bonus_remaps ?: 0 }}</dd>
                    <dt>{{ trans('web::seat.last_remap_date') }}</dt>
                    <dd>{{ $character->pilot_attributes->last_remap_date ?: trans('web::seat.no_remap') }}</dd>
                    <dt>{{ trans('web::seat.accrued_remap_cooldown_date') }}</dt>
                    <dd>{{ $character->pilot_attributes->accrued_remap_cooldown_date ?: carbon() }}</dd>
                </dl>
            </div>

        </div>

    </div>
</div>