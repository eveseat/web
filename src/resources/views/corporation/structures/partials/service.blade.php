<li>
    {{ $service->name }} :
    @if($service->state == 'online')
        <span class="text text-green">{{ ucfirst($service->state) }}</span>
    @else
        <span class="text text-red">{{ ucfirst($service->state) }}</span>
    @endif
</li>