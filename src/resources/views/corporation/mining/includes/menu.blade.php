<ul class="nav nav-pills">
    <li role="presentation" @if($sub_viewname == 'ledger')class="active"@endif>
        <a href="{{ route('corporation.view.mining_ledger', request()->route()->parameter('corporation_id')) }}">Ledger</a>
    </li>
</ul>
