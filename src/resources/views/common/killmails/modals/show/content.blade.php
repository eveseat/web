<div class="media">
    {!! img('types', 'render', $killmail->victim->ship->typeID, 128, ['class' => 'align-self-center mr-3', 'alt' => $killmail->victim->ship->typeName], false) !!}
    <div class="media-body">
        <h5 class="mt-0">{{ $killmail->victim->ship->typeName }} <small><i>({{ $killmail->victim->ship->group->groupName }})</i></small></h5>
        <p class="text-justify">{!! $killmail->victim->ship->description !!}</p>
    </div>
</div>
<div class="media">
    <div class="media-body">
        <h5 class="mt-0">{{ $killmail->victim->character->name }}</h5>
        <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item">
                <b>Solar System</b>
                <span class="text-muted float-right">
                    @include('web::partials.system', [
                        'system' => $killmail->detail->solar_system->name,
                        'security' => $killmail->detail->solar_system->security
                    ])
                </span>
            </li>
            <li class="list-group-item">
                <b>Damage Taken</b>
                <span class="text-muted float-right">{{ number_format($killmail->victim->damage_taken) }}</span>
            </li>
            <li class="list-group-item">
                <b>Date/Time</b>
                <span class="text-muted float-right">{{ $killmail->detail->killmail_time }}</span>
            </li>
            <li class="list-group-item">
                <b>Fitted</b>
                <span class="text-muted float-right">{{ number_format($killmail->victim->fitted_estimate, 2) }}</span>
            </li>
            <li class="list-group-item">
                <b>Dropped</b>
                <span class="text-success float-right">{{ number_format($killmail->victim->dropped_estimate, 2) }}</span>
            </li>
            <li class="list-group-item">
                <b>Destroyed</b>
                <span class="text-danger float-right">{{ number_format($killmail->victim->destroyed_estimate, 2) }}</span>
            </li>
            <li class="list-group-item">
                <b>Total</b>
                <span class="text-muted float-right">{{ number_format($killmail->victim->total_estimate, 2) }}</span>
            </li>
        </ul>
    </div>
    <div class="mt-4 ml-4">
        {!! img('characters', 'portrait', $killmail->victim->character->entity_id, 128, ['class' => 'align-self-center', 'alt' => $killmail->victim->character->name], false) !!}
        <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item border-0 pt-3 pb-0">@include('web::partials.corporation', ['corporation' => $killmail->victim->corporation])</li>
            <li class="list-group-item border-0 pt-3 pb-0">@include('web::partials.alliance', ['alliance' => $killmail->victim->alliance])</li>
        </ul>
    </div>
</div>

<h5>{{ trans_choice('web::kills.attackers', 2) }}</h5>
<table class="table table-striped table-hover" id="killmail-attackers">
    <thead>
        <tr>
            <th>Entity</th>
            <th>Ship</th>
            <th>Weapon</th>
            <th>Damages</th>
        </tr>
    </thead>
    @foreach($killmail->attackers()->orderByDesc('final_blow')->orderByDesc('damage_done')->get() as $attacker)
        <tr @if($attacker->final_blow)class="bg-gradient-primary"@endif>
            <td>@include('web::common.killmails.entity', ['entity' => $attacker])</td>
            <td>@include('web::partials.type', [
                'type_id' => $attacker->ship->typeID,
                'type_name' => $attacker->ship->typeName,
            ])</td>
            <td>
                @if($attacker->weapon)
                    @include('web::partials.type', [
                        'type_id' => $attacker->weapon->typeID,
                        'type_name' => $attacker->weapon->typeName,
                    ])
                @endif
            </td>
            <td>{{ number_format($attacker->damage_done) }} ({{ number_format($attacker->damage_done / $killmail->victim->damage_taken * 100, 2) }}%)</td>
        </tr>
    @endforeach
</table>

<h5>{{ trans_choice('web::kills.items', 2) }}</h5>
<table class="table table-striped table-hover" id="killmail-items">
    <thead>
        <tr>
            <th>Type</th>
            <th>Destroyed</th>
            <th>Dropped</th>
            <th>Total</th>
        </tr>
    </thead>
    @foreach($killmail->victim->items as $item)
        <tr>
            <td>@include('web::partials.type', ['type_id' => $item->typeID, 'type_name' => $item->typeName])</td>
            <td>{{ number_format($item->pivot->quantity_destroyed * $item->price->average_price, 2) }} ({{ number_format($item->pivot->quantity_destroyed ?: 0) }})</td>
            <td>{{ number_format($item->pivot->quantity_dropped * $item->price->average_price, 2) }} ({{ number_format($item->pivot->quantity_dropped ?: 0) }})</td>
            <td>{{ number_format(($item->pivot->quantity_destroyed + $item->pivot->quantity_dropped) * $item->price->average_price, 2) }} ({{ number_format($item->pivot->quantity_destroyed + $item->pivot->quantity_dropped) }})</td>
        </tr>
    @endforeach
</table>