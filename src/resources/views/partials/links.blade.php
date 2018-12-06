@if (!in_array($type, ['corporation', 'alliance']))
  <a href="http://eveskillboard.com/pilot/{{ $id }}"
     target="_blank" class="id-to-name" data-id="{{$id}}">
    <img src="{{ asset('web/img/eveskillboard.png') }}">
  </a>
@endif
@if (!in_array($type, ['corporation', 'alliance']))
  <a href="http://eve-search.com/search/author/{{ $id }}"
     target="_blank" class="id-to-name" data-id="{{$id}}">
    <img src="{{ asset('web/img/evesearch.png') }}">
  </a>
@endif
@if ($type == 'corporation')
  <a href="http://evewho.com/corp/{{ $id }}"
     target="_blank" class="id-to-name" data-id="{{$id}}">
    <img src="{{ asset('web/img/evewho.png') }}">
  </a>
@elseif ($type == 'alliance')
  <a href="http://evewho.com/alli/{{ $id }}"
     target="_blank" class="id-to-name" data-id="{{$id}}">
    <img src="{{ asset('web/img/evewho.png') }}">
  </a>
@else
  <a href="http://evewho.com/pilot/{{ $id }}"
     target="_blank" class="id-to-name" data-id="{{$id}}">
    <img src="{{ asset('web/img/evewho.png') }}">
  </a>
@endif
@if (in_array($type, ['corporation', 'alliance', 'character']))
  <a href="https://zkillboard.com/{{ $type }}/{{ $id }}"
     target="_blank">
    <img src="{{ asset('web/img/zkillboard.png') }}">
  </a>
@endif
<a href="http://eve-prism.com/?view={{ $type }}&name={{ $id }}"
   target="_blank" class="id-to-name" data-id="{{$id}}">
  <img src="{{ asset('web/img/eve-prism.png') }}"/>
</a>