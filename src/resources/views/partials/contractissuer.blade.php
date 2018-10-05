{!! img('auto', $row->issuer_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
<span class="id-to-name" data-id="{{ $row->issuer_id }}">{{ trans('web::seat.unknown') }}</span>
