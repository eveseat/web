<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">{{ trans_choice('web::seat.implants', 0) }}</h3>
    </div>
    <div class="card-body">

        @if($character->implants->isNotEmpty())

            <ul>

                @foreach($character->implants as $implant)
                    <li>{{ $implant->type->typeName }}</li>
                @endforeach

            </ul>

        @else
            {{ trans('web::seat.no_implants') }}
        @endif

    </div>
    <div class="card-footer">
        {{ $character->implants->count() }} {{ trans_choice('web::seat.implants', $character->implants->count()) }}
    </div>
</div>