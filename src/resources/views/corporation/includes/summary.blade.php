<div class="card card-gray card-outline">
  <div class="card-header">
    <h3 class="card-title">{{ trans('web::seat.summary') }}</h3>
  </div>
  <div class="card-body box-profile">

    <div class="text-center">

      {!! img('corporations', 'logo', $sheet->corporation_id, 128, ['class' => 'profile-user-img img-responsive img-circle']) !!}

    </div>

    <h3 class="profile-username text-center">
      {{ $sheet->name }}
    </h3>

    @if($sheet->alliance_id)
      <p class="text-muted text-center">
        <span class="id-to-name" data-id="{{ $sheet->alliance_id }}">{{ trans('web::seat.unknown') }}</span>
      </p>
    @endif

    <hr>

    <dl>

      @if($sheet->alliance_id)
        <dt>{{ trans('web::seat.alliance') }}</dt>
        <dd><span class="id-to-name" data-id="{{ $sheet->alliance_id }}">{{ trans('web::seat.unknown') }}</span></dd>
      @endif

      <dt>{{ trans('web::seat.ticker') }}</dt>
      <dd>{{ $sheet->ticker }}</dd>

      <dt>{{ trans('web::seat.ceo') }}</dt>
      <dd>
        <a href="{{ route('character.view.sheet', ['character_id' => $sheet->ceo_id]) }}">
          {!! img('characters', 'portrait', $sheet->ceo_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
          <span class="id-to-name" data-id="{{ $sheet->ceo_id }}">{{ trans('web::seat.unknown') }}</span>
        </a>
      </dd>

      <dt>{{ trans('web::seat.home_station') }}</dt>
      <dd>{{ optional($sheet->homeStation)->name }}</dd>

      <dt>{{ trans('web::seat.url') }}</dt>
      <dd>
        <a href="{{ $sheet->url }}" target="_blank">{{ $sheet->url }}</a>
      </dd>

      <dt>{{ trans('web::seat.tax_rate') }}</dt>
      <dd>{{ number_format($sheet->tax_rate * 100) }}%</dd>

      <dt>{{ trans('web::seat.member_count') }}</dt>
      <dd>
        @if(!is_null($sheet->memberLimit) && $sheet->memberLimit > 0)
        {{ $sheet->member_count }} / {{ $sheet->memberLimit }}
        @else
        {{ $sheet->member_count }}
        @endif
      </dd>

      <dt>{{ trans('web::seat.last_update') }}</dt>
      <dd>
        <span data-toggle="tooltip"
              title="" data-original-title="{{ $sheet->updated_at }}">
          {{ human_diff($sheet->updated_at) }}
        </span>
      </dd>

      <dt></dt>
    </dl>
  </div>

</div>

@push('javascript')
@include('web::includes.javascript.id-to-name')
@endpush
