<div class="pb-3">

  <ul class="nav nav-pills">
    @can('character.journal', request()->character)
      <li role="presentation" class="nav-item">
        <a href="{{ route('character.view.journal', $summary) }}" class="nav-link @if ($sub_viewname == 'journal') active @endif">Journal</a>
      </li>
    @endif
    @can('character.transaction', request()->character)
      <li role="presentation" class="nav-item">
        <a href="{{ route('character.view.transactions', $summary) }}" class="nav-link @if($sub_viewname == 'transactions') active @endif">Transactions</a>
      </li>
    @endcan
  </ul>

</div>
