<div class="card mb-3">
  {!! img('corporations', 'logo', $corporation->corporation_id, 512, ['class' => 'card-img-top bg-dark bg-gradient']) !!}
  <div class="card-body">
    <h5 class="card-title text-center">{{ $corporation->name }}</h5>
    <!-- affiliation -->
    @if($corporation->alliance_id)
      <div class="list-group list-group-flush list-group-hoverable mb-3">
        <!-- ceo -->
        <div class="list-group-item ps-0 pe-0">
          <div class="row align-items-center">
            <div class="col-auto">
              {!! img('characters', 'portrait', $corporation->ceo_id, 128, ['class' => 'avatar']) !!}
            </div>
            <div class="col">
              @if(\Seat\Eveapi\Models\Character\CharacterInfo::find($corporation->ceo_id))
                <a href="{{ route('seatcore::character.view.default', $corporation->ceo_id) }}" class="text-body d-block stretched-link">{{ $corporation->ceo->name }}</a>
              @else
                <span class="text-body d-block id-to-name" data-id="{{ $corporation->ceo_id }}">{{ trans('web::seat.unknown') }}</span>
              @endif
              <span class="text-muted mt-n1">CEO</span>
            </div>
          </div>
        </div>
        <!-- ./ceo -->
        <!-- alliance -->
        @if($corporation->alliance_id)
        <div class="list-group-item ps-0 pe-0">
          <div class="row align-items-center">
            <div class="col-auto">
              {!! img('alliances', 'logo', $corporation->alliance_id, 128, ['class' => 'avatar']) !!}
            </div>
            <div class="col">
              <a href="{{ route('seatcore::alliance.view.default', $corporation->alliance_id) }}" class="text-body d-block stretched-link">
                <span class="id-to-name" data-id="{{ $corporation->alliance_id }}">{{ trans('web::seat.unknown') }}</span>
              </a>
              <span class="text-muted mt-n1">Alliance</span>
            </div>
          </div>
        </div>
        @endif
        <!-- ./alliance -->
        <!-- faction -->
        @if($corporation->faction_id)
        <div class="list-group-item ps-0 pe-0">
          <div class="row align-items-center">
            <div class="col-auto">
              {!! img('corporations', 'logo', $corporation->faction_id, 128, ['class' => 'avatar']) !!}
            </div>
            <div class="col">
              <span class="text-body d-block id-to-name" data-id="{{ $corporation->faction_id }}">{{ trans('web::seat.unknown') }}</span>
              <span class="text-muted mt-n1">Faction</span>
            </div>
          </div>
        </div>
        @endif
        <!-- ./faction -->
      </div>
    @endif
    <!-- ./affiliation -->
    <!-- information -->
    <dl class="row">
      <dt class="col-5">Ticker</dt>
      <dd class="col-7">{{ $corporation->ticker }}</dd>
      <dt class="col-5">Home Station</dt>
      <dd class="col-7">{{ optional($corporation->homeStation)->name }}</dd>
      <dt class="col-5">Url</dt>
      <dd class="col-7">
        @if($corporation->url)
          <a href="{{ $corporation->url }}" target="_blank">
            <i class="fas fa-link"></i>
            External link
          </a>
        @endif
      </dd>
      <dt class="col-5">Tax Rate</dt>
      <dd class="col-7">{{ number_format($corporation->tax_rate * 100) }}%</dd>
      <dt class="col-5">Member Count</dt>
      <dd class="col-7">
        @if(!is_null($corporation->memberLimit) && $corporation->memberLimit > 0)
          {{ $corporation->member_count }} / {{ $corporation->memberLimit }}
        @else
          {{ $corporation->member_count }}
        @endif
      </dd>
    </dl>
    <!-- ./information -->
  </div>
</div>

@push('javascript')
@include('web::includes.javascript.id-to-name')
@endpush
