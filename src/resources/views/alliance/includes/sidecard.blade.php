<div class="card mb-3">
  {!! img('alliances', 'logo', $alliance->alliance_id, 128, ['class' => 'card-img-top bg-dark bg-gradient']) !!}
  <div class="card-body">
    <h5 class="card-title text-center">{{ $alliance->name }}</h5>
    <!-- affiliation -->
    <div class="list-group list-group-flush list-group-hoverable mb-3">
      <!-- creator -->
      <div class="list-group-item ps-0 pe-0">
        <div class="row align-items-center">
          <div class="col-auto">
            {!! img('characters', 'portrait', $alliance->creator_id, 128, ['class' => 'avatar']) !!}
          </div>
          <div class="col">
            @if(\Seat\Eveapi\Models\Character\CharacterInfo::find($alliance->creator_id))
              <a href="{{ route('seatcore::character.view.default', $alliance->creator_id) }}" class="text-body d-block stretched-link">{{ $alliance->creator->name }}</a>
            @else
              <span class="text-body d-block id-to-name" data-id="{{ $alliance->creator_id }}">{{ trans('web::seat.unknown') }}</span>
            @endif
            <span class="text-muted mt-n1">Creator</span>
          </div>
        </div>
      </div>
      <!-- ./creator -->
      <!-- creator-corporation -->
      <div class="list-group-item ps-0 pe-0">
        <div class="row align-items-center">
          <div class="col-auto">
            {!! img('corporations', 'logo', $alliance->creator_corporation_id, 128, ['class' => 'avatar']) !!}
          </div>
          <div class="col">
            @if(\Seat\Eveapi\Models\Corporation\CorporationInfo::find($alliance->creator_corporation_id))
              <a href="{{ route('seatcore::corporation.view.default', $alliance->creator_corporation_id) }}" class="text-body d-block stretched-link">{{ $alliance->creator_corporation->name }}</a>
            @else
              <span class="text-body d-block id-to-name" data-id="{{ $alliance->creator_corporation_id }}">{{ trans('web::seat.unknown') }}</span>
            @endif
            <span class="text-muted mt-n1">Creator Corporation</span>
          </div>
        </div>
      </div>
      <!-- ./creator-corporation -->
      <!-- executor -->
      <div class="list-group-item ps-0 pe-0">
        <div class="row align-items-center">
          <div class="col-auto">
            {!! img('corporations', 'logo', $alliance->executor_corporation_id, 128, ['class' => 'avatar']) !!}
          </div>
          <div class="col">
            @if(\Seat\Eveapi\Models\Corporation\CorporationInfo::find($alliance->executor_corporation_id))
              <a href="{{ route('seatcore::corporation.view.default', $alliance->executor_corporation_id) }}" class="text-body d-block stretched-link">{{ $alliance->executor->name }}</a>
            @else
              <span class="text-body d-block id-to-name" data-id="{{ $alliance->executor_corporation_id }}">{{ trans('web::seat.unknown') }}</span>
            @endif
            <span class="text-muted mt-n1">Executor</span>
          </div>
        </div>
      </div>
      <!-- ./executor -->
      <!-- faction -->
      @if($alliance->faction_id)
        <div class="list-group-item ps-0 pe-0">
          <div class="row align-items-center">
            <div class="col-auto">
              {!! img('corporations', 'logo', $alliance->faction_id, 128, ['class' => 'avatar']) !!}
            </div>
            <div class="col">
              <span class="text-body d-block id-to-name" data-id="{{ $alliance->faction_id }}">{{ trans('web::seat.unknown') }}</span>
              <span class="text-muted mt-n1">Faction</span>
            </div>
          </div>
        </div>
      @endif
      <!-- ./faction -->
    </div>
    <!-- ./affiliation -->
    <!-- information -->
    <dl class="row">
      <dt class="col-5">{{ trans('web::seat.ticker') }}</dt>
      <dd class="col-7">{{ $alliance->ticker }}</dd>
      <dt class="col-5">{{ trans('web::seat.created') }}</dt>
      <dd class="col-7">{{ human_diff($alliance->date_founded) }}</dd>
    </dl>
    <!-- ./information -->
  </div>
</div>

@push('javascript')
@include('web::includes.javascript.id-to-name')
@endpush
