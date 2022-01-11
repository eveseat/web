@if(array_key_exists('plural', $data))
    <span class="nav-link-title">{{ trans_choice($data['label'], $data['plural'] ? 0 : 1) }}</span>
@else
    <span class="nav-link-title">{{ trans($data['label']) }}</span>
@endif