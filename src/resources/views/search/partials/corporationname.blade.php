{!! img('corporation', $row->corporation_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
<span class="id-to-name" data-id="{{ $row->corporation_id }}">{{ trans('web::seat.unknown') }}</span>
