<td>
    <div class="position-relative progress"
         data-toggle="tooltip"
         title="{{ $row->value }} / {{ $row->max }}">

        {{-- Actual fill bar --}}
        <div class="progress-bar"
             role="progressbar"
             style="width: {{ ($row->max > 0) ? ($row->value * 100 / $row->max) : 0 }}%;"
             aria-valuenow="{{ $row->value }}"
             aria-valuemin="{{ $row->min }}"
             aria-valuemax="{{ $row->max }}">
        </div>

        {{-- Centered overlay text (full width, hoverable, no pointer events) --}}
        <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center
                    {{ (($row->max > 0 ? ($row->value * 100 / $row->max) : 0) > 50) ? 'text-light' : 'text-dark' }}"
             style="top: 0; left: 0; pointer-events: none;">

            @if($row->showval)
                {{ round(($row->max > 0) ? ($row->value * 100 / $row->max) : 0) }}%
            @endif
        </div>

    </div>
</td>
