{{ $row->itemName }}

<span class="
  @if($row->security >= 0.5)
    text-green
@elseif($row->security < 0.5 && $row->security > 0.0)
    text-warning
@else
    text-red
@endif">
  <i>({{ round($row->security,  2) }})</i>
</span>
