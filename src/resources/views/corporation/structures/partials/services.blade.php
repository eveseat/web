<ul class="mb-0">
    @foreach($row->services as $service)
        @include('web::corporation.structures.partials.service', compact('service'))
    @endforeach
</ul>