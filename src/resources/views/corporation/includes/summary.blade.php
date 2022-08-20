<div class="card card-gray card-outline">
  <div class="card-header">
    <h3 class="card-title">{{ trans('web::seat.summary') }}</h3>
  </div>
  <div class="card-body box-profile">

    <div class="text-center">

      {!! img('corporations', 'logo', $corporation->corporation_id, 128, ['class' => 'profile-user-img img-responsive img-circle']) !!}

    </div>

    <h3 class="profile-username text-center">
      {{ $corporation->name }}
    </h3>

    @if($corporation->alliance_id)
      <p class="text-muted text-center">
        <span class="id-to-name" data-id="{{ $corporation->alliance_id }}">{{ trans('web::seat.unknown') }}</span>
      </p>
    @endif

    <hr>

    <dl>

      @if($corporation->alliance_id)
        <dt>{{ trans_choice('web::seat.alliance', 1) }}</dt>
        <dd><span class="id-to-name" data-id="{{ $corporation->alliance_id }}">{{ trans('web::seat.unknown') }}</span></dd>
      @endif

      <dt>{{ trans('web::seat.ticker') }}</dt>
      <dd>{{ $corporation->ticker }}</dd>

      <dt>{{ trans('web::seat.ceo') }}</dt>
      <dd>
        @include('web::partials.character', ['character' => $corporation->ceo])
      </dd>

      <dt>{{ trans('web::seat.home_station') }}</dt>
      <dd>{{ optional($corporation->homeStation)->name }}</dd>

      <dt>{{ trans('web::seat.url') }}</dt>
      <dd>
        <a href="{{ $corporation->url }}" target="_blank">{{ $corporation->url }}</a>
      </dd>

      <dt>{{ trans('web::seat.tax_rate') }}</dt>
      <dd>{{ number_format($corporation->tax_rate * 100) }}%</dd>

      <dt>{{ trans('web::seat.member_count') }}</dt>
      <dd>
        @if(!is_null($corporation->memberLimit) && $corporation->memberLimit > 0)
        {{ $corporation->member_count }} / {{ $corporation->memberLimit }}
        @else
        {{ $corporation->member_count }}
        @endif
      </dd>

    </dl>
  </div>

</div>

@push('javascript')
@include('web::includes.javascript.id-to-name')
@endpush
