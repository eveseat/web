@if (!in_array($row->contact_type, ['corporation', 'alliance']))
  <a href="http://eveskillboard.com/pilot/{{ $row->contact_id }}"
     target="_blank" class="id-to-name" data-id="{{$row->contact_id}}">
    <img src="{{ asset('web/img/eveskillboard.png') }}">
  </a>
@endif
@if (!in_array($row->contact_type, ['corporation', 'alliance']))
  <a href="http://eve-search.com/search/author/{{ $row->contact_id }}"
     target="_blank" class="id-to-name" data-id="{{$row->contact_id}}">
    <img src="{{ asset('web/img/evesearch.png') }}">
  </a>
@endif
@if ($row->contact_type == 'corporation')
  <a href="http://evewho.com/corp/{{ $row->contact_id }}"
     target="_blank" class="id-to-name" data-id="{{$row->contact_id}}">
    <img src="{{ asset('web/img/evewho.png') }}">
  </a>
@elseif ($row->contact_type == 'alliance')
  <a href="http://evewho.com/alli/{{ $row->contact_id }}"
     target="_blank" class="id-to-name" data-id="{{$row->contact_id}}">
    <img src="{{ asset('web/img/evewho.png') }}">
  </a>
@else
  <a href="http://evewho.com/pilot/{{ $row->contact_id }}"
     target="_blank" class="id-to-name" data-id="{{$row->contact_id}}">
    <img src="{{ asset('web/img/evewho.png') }}">
  </a>
@endif
@if (in_array($row->contact_type, ['corporation', 'alliance', 'character']))
  <a href="https://zkillboard.com/{{ $row->contact_type }}/{{ $row->contact_id }}"
     target="_blank">
    <img src="{{ asset('web/img/zkillboard.png') }}">
  </a>
@endif
<a href="http://eve-prism.com/?view={{ $row->contact_type }}&name={{ $row->contact_id }}"
   target="_blank" class="id-to-name" data-id="{{$row->contact_id}}">
  <img src="{{ asset('web/img/eve-prism.png') }}"/>
</a>