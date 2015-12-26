<div class="progress">
  <div class="progress-bar" role="progressbar"
       aria-valuenow="60" aria-valuemin="0"
       aria-valuemax="100"
       style="width:
        @if($partial == 0 || $full == 0) 0% @else {{ 100 * ($partial / $full) }}% @endif">
  </div>
</div>
