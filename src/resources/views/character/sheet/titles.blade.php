<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">{{ trans_choice('web::seat.corporation_titles', 0) }}</h3>
    </div>
    <div class="card-body">
        @if($character->titles->isNotEmpty())
            <ul class="list-unstyled">
                @foreach($character->titles as $title)
                    <li>{!! clean_ccp_html($title->name) !!}</li>
                @endforeach
            </ul>
        @else
            {{ trans('web::seat.no_corporation_titles') }}
        @endif
    </div>
    <div class="card-footer">
        {{ $character->titles->count() }} {{ trans_choice('web::seat.corporation_titles', $character->titles->count()) }}
    </div>
</div>