@if($sovereignty->faction_id!== null)
    @include('web::partials.faction', ['faction' => $sovereignty->faction])
@elseif($sovereignty->alliance_id!== null)
    @include('web::partials.alliance', ['alliance' => $sovereignty->alliance])
@elseif($sovereignty->corporation_id!== null)
    @include('web::partials.corporation', ['corporation' => $sovereignty->corporation])
@endif