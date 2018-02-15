<div style="padding-bottom: 15px;">

  <ul class="nav nav-pills">
    <li role="presentation" class="@if ($sub_viewname == 'journal') active @endif">
      <a href="{{ route('character.view.journal', $summary->character_id) }}">Journal</a>
    </li>
    <li role="presentation" class="@if($sub_viewname == 'transactions') active @endif">
      <a href="{{ route('character.view.transactions', $summary->character_id) }}">Transactions</a>
    </li>

  </ul>

</div>
