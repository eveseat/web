<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{ trans('web::seat.summary') }}</h3>
  </div>
  <div class="panel-body">

    <div class="box-body box-profile">

      {!! img('corporation', $sheet->corporationID, 128, ['class' => 'profile-user-img img-responsive img-circle']) !!}
      <h3 class="profile-username text-center">
        {{ $sheet->corporationName }}
      </h3>

      @if($sheet->allianceID)
        <p class="text-muted text-center">
          {{ $sheet->allianceName }}
        </p>
      @endif

      <hr>

      <dl>

        @if($sheet->allianceID)
          <dt>{{ trans('web::seat.alliance') }}</dt>
          <dd>{{ $sheet->allianceName }}</dd>
        @endif

        <dt>{{ trans('web::seat.ticker') }}</dt>
        <dd>{{ $sheet->ticker }}</dd>

        <dt>{{ trans('web::seat.ceo') }}</dt>
        <dd>
          <a href="{{ route('character.view.sheet', ['character_id' => $sheet->ceoID]) }}">
            {!! img('character', $sheet->ceoID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
            {{ $sheet->ceoName }}
          </a>
        </dd>

        <dt>{{ trans('web::seat.home_station') }}</dt>
        <dd>{{ $sheet->stationName }}</dd>

        <dt>{{ trans('web::seat.url') }}</dt>
        <dd>
          <a href="{{ $sheet->url }}" target="_blank">{{ $sheet->url }}</a>
        </dd>

        <dt>{{ trans('web::seat.tax_rate') }}</dt>
        <dd>{{ number($sheet->taxRate) }}%</dd>

        <dt>{{ trans('web::seat.member_count') }}</dt>
        <dd>
          {{ $sheet->memberCount }} / {{ $sheet->memberLimit }}
        </dd>

        <dt>{{ trans('web::seat.last_update') }}</dt>
        <dd>{{ $sheet->updated_at }}</dd>

        <dt></dt>
      </dl>
    </div>

  </div>
</div>
