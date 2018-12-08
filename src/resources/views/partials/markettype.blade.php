<span data-toggle="tooltip"
      title="" data-original-title="{{ $row->stationName }}">
  <i class="fa fa-map-marker"></i>
</span>
@if (request('all_linked_characters') === "true")
  {!! img('character', $row->character_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
@endif
{!! img('type', $row->type_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
{{ $row->typeName }}
