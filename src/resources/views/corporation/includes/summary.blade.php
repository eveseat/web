<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Summary</h3>
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
          <dt>Alliance</dt>
          <dd>{{ $sheet->allianceName }}</dd>
        @endif

        <dt>Ticker</dt>
        <dd>{{ $sheet->ticker }}</dd>

        <dt>CEO</dt>
        <dd>
          <a href="{{ route('character.view.sheet', ['character_id' => $sheet->ceoID]) }}">
            {!! img('character', $sheet->ceoID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
            {{ $sheet->ceoName }}
          </a>
        </dd>

        <dt>Home Station</dt>
        <dd>{{ $sheet->stationName }}</dd>

        <dt>URL</dt>
        <dd>
          <a href="{{ $sheet->url }}" target="_blank">{{ $sheet->url }}</a>
        </dd>

        <dt>Tax Rate</dt>
        <dd>{{ $sheet->taxRate }}%</dd>

        <dt>Member Count</dt>
        <dd>
          {{ $sheet->memberCount }} / {{ $sheet->memberLimit }}
        </dd>

        <dt></dt>
      </dl>
    </div>

  </div>
</div>
