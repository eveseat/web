<div class="mb-3">
    <ul class="nav nav-pills">
        <li role="presentation" class="nav-item">
            <a href="{{ route('corporation.view.mining_ledger', request()->route()->parameter('corporation_id')) }}" class="nav-link @if($sub_viewname == 'ledger') active @endif">Ledger</a>
        </li>
    </ul>
</div>
