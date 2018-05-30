@foreach ($widgets as $widget)
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ $widget->getTitle() }}</h3>
    </div>
    <div class="panel-body">
        @include($widget->getBodyTemplate(), ['dataset' => $widget->getDataset()])
    </div>
    @if(! is_null($widget->getFooter()))
    <div class="panel-footer">{{ $widget->getFooter() }}</div>
    @endif
</div>
@endforeach