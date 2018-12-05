@if($row->alliance_id)
  {!! img('alliance', $row->alliance_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
  <span class="id-to-name" data-id="{{ $row->alliance_id }}">{{ trans('web::seat.unknown') }}</span>
@endif
