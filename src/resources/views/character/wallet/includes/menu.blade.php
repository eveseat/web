<div class="pb-3">

  <ul class="nav nav-pills">
    @if(auth()->user()->has('character.journal'))
    <li role="presentation" class="nav-item">
      <a href="{{ route('character.view.journal', $summary->character_id) }}" class="nav-link @if ($sub_viewname == 'journal') active @endif">Journal</a>
    </li>
    @endif
    @if(auth()->user()->has('character.transactions'))
    <li role="presentation" class="nav-item">
      <a href="{{ route('character.view.transactions', $summary->character_id) }}" class="nav-link @if($sub_viewname == 'transactions') active @endif">Transactions</a>
    </li>
    @endif
  </ul>

</div>
