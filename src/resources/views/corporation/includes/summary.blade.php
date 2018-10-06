<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{ trans('web::seat.summary') }}</h3>
  </div>
  <div class="panel-body">

    <div class="box-body box-profile">

      {!! img('corporation', $sheet->corporation_id, 128, ['class' => 'profile-user-img img-responsive img-circle']) !!}
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
            {!! img('character', $sheet->ceo_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
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
        <dd>{{ number($sheet->tax_rate * 100) }}%</dd>

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
</div>

@push('javascript')
@include('web::includes.javascript.id-to-name')
@endpush
