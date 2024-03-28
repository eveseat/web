<div class="pb-3">

  <ul class="nav nav-pills">
    @can('corporation.journal', $corporation)
      <li role="presentation" class="nav-item">
        <a href="{{ route('seatcore::corporation.view.journal', $corporation) }}" class="nav-link @if($sub_viewname == 'journal') active @endif">Journal</a>
      </li>
    @endif
    @can('corporation.transaction', $corporation)
      <li role="presentation" class="nav-item">
        <a href="{{ route('seatcore::corporation.view.transactions', $corporation) }}" class="nav-link @if($sub_viewname == 'transactions') active @endif">Transactions</a>
      </li>
    @endif
  </ul>

</div>
