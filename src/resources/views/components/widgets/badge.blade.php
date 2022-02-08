<div class="card card-sm" {{ $attributes }}>
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-auto">
                <span class="{{ $color }} text-white avatar">
                    <i class="{{ $icon }}"></i>
                </span>
            </div>
            <div class="col">
                <div class="fw-bold">{{ $title }}</div>
                <div class="text-muted fst-italic">{{ $value }}</div>
            </div>
        </div><!-- ./row -->
    </div><!-- ./card-body -->
</div><!-- ./card -->