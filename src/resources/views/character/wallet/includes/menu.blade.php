<div class="pb-3">

  <ul class="nav nav-pills">
    @can('character.journal', $character)
      <li role="presentation" class="nav-item">
        <a href="{{ route('seatcore::character.view.journal', $character) }}" class="nav-link @if ($sub_viewname == 'journal') active @endif">Journal</a>
      </li>
    @endif
    @can('character.transaction', $character)
      <li role="presentation" class="nav-item">
        <a href="{{ route('seatcore::character.view.transactions', $character) }}" class="nav-link @if($sub_viewname == 'transactions') active @endif">Transactions</a>
      </li>
    @endcan
  </ul>

</div>
