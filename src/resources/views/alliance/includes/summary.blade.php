<div class="card card-gray card-outline">
  <div class="card-header">
    <h3 class="card-title">{{ trans('web::seat.summary') }}</h3>
  </div>
  <div class="card-body box-profile">

    <div class="text-center">

      {!! img('alliances', 'logo', $alliance->alliance_id, 128, ['class' => 'profile-user-img img-responsive img-circle']) !!}

    </div>

    <h3 class="profile-username text-center">
      {{ $alliance->name }}
    </h3>

    <hr>

    <dl>

      <dt>{{ trans('web::seat.ticker') }}</dt>
      <dd>{{ $alliance->ticker }}</dd>

      <dt>{{ trans('web::seat.executor') }}</dt>
      <dd>
        @include('web::partials.corporation', ['corporation' => $alliance->executor])
      </dd>

      <dt>{{ trans('web::seat.created') }}</dt>
      <dd>
        {{ $alliance->date_founded }}
      </dd>

      <dt>{{ trans('web::seat.created_by') }}</dt>
      <dd>
        @include('web::partials.character', ['character' => $alliance->creator])
      </dd>

      <dt>{{ trans('web::seat.created_by_corporation') }}</dt>
      <dd>
        @include('web::partials.corporation', ['corporation' => $alliance->creator_corporation])
      </dd>

    </dl>
  </div>

</div>

@push('javascript')
@include('web::includes.javascript.id-to-name')
@endpush
