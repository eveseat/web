<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">{{ trans('web::seat.jump_fatigue') }}
            &amp; {{ trans_choice('web::seat.jump_clones', 0) }}</h3>
    </div>
    <div class="card-body">

        <dl>
            <dt>{{ trans('web::seat.jump_fatigue') }}</dt>
            <dd>

                @if(carbon($character->fatigue->jump_fatigue_expire_date)->gt(carbon(null)))
                    {{ $character->fatigue->jump_fatigue_expire_date }}
                    <span class="float-right">Ends approx {{ human_diff($character->fatigue->jump_fatigue_expire_date) }}</span>
                @else
                    None
                @endif

            </dd>

            <dt>{{ trans('web::seat.jump_act_timer') }}</dt>
            <dd>
                @if(carbon($character->clone->last_clone_jump_date)->gt(carbon(null)))
                    {{ $character->clone->last_clone_jump_date }}
                    <span class="float-right">Ends approx {{ human_diff($character->clone->last_clone_jump_date) }}</span>
                @else
                    {{ trans('web::seat.none') }}
                @endif
            </dd>

            <dt>{{ trans_choice('web::seat.jump_clones', 0) }}</dt>
            <dd>

                @if($character->jump_clones->isNotEmpty())

                    <ul>

                        @foreach($character->jump_clones as $clone)
                            <li>
                                @if(! is_null($clone->name) && ! empty($clone->name))
                                    ({{ $clone->name }})
                                @endif

                                @if(! is_null($clone->location))
                                    Located at <b>{{ $clone->location->name }}</b>
                                @else
                                    Location is unknown
                                @endif
                            </li>
                        @endforeach

                    </ul>

                @else
                    {{ trans('web::seat.no_jump_clones') }}
                @endif

            </dd>

        </dl>

    </div>
    <div class="card-footer">
        {{ $character->jump_clones->count() }} {{ trans_choice('web::seat.jump_clones', $character->jump_clones->count()) }}
    </div>
</div>