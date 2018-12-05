@if($row->first_party_id != 0)
  {!! img('auto', $row->first_party_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
  <span class="id-to-name" data-id="{{ $row->first_party_id }}">{{ trans('web::seat.unknown') }}</span>
@else
  n/a
@endif
