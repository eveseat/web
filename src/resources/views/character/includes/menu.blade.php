<div style="padding-bottom: 15px;">

  <ul class="nav nav-pills">
    <li role="presentation" class="@if ($viewname == 'assets') active @endif">
      <a href="{{ route('character.view.assets', $summary->characterID) }}">Assets</a></li>
    <li role="presentation" class="@if ($viewname == 'calendar') active @endif">
      <a href="{{ route('character.view.calendar', $summary->characterID) }}">Calender</a></li>
    <li role="presentation" class="@if ($viewname == 'contacts') active @endif">
      <a href="{{ route('character.view.contacts', $summary->characterID) }}">Contacts</a></li>
    <li role="presentation" class="@if ($viewname == 'contracts') active @endif">
      <a href="{{ route('character.view.contracts', $summary->characterID) }}">Contracts</a></li>
    <li role="presentation" class="@if ($viewname == 'industry') active @endif">
      <a href="{{ route('character.view.industry', $summary->characterID) }}">Industry</a></li>
    <li role="presentation" class="@if ($viewname == 'killmails') active @endif">
      <a href="{{ route('character.view.killmails', $summary->characterID) }}">Killmails</a></li>
    <li role="presentation" class="@if ($viewname == 'mail') active @endif">
      <a href="{{ route('character.view.mail', $summary->characterID) }}">Mail</a></li>
    <li role="presentation" class="@if ($viewname == 'market') active @endif">
      <a href="{{ route('character.view.market', $summary->characterID) }}">Market</a></li>
    <li role="presentation" class="@if ($viewname == 'notifications') active @endif">
      <a href="{{ route('character.view.notifications', $summary->characterID) }}">Notifications</a></li>
    <li role="presentation" class="@if ($viewname == 'pi') active @endif">
      <a href="{{ route('character.view.pi', $summary->characterID) }}">PI</a></li>
    <li role="presentation" class="@if ($viewname == 'research') active @endif">
      <a href="{{ route('character.view.research', $summary->characterID) }}">Research</a></li>
    <li role="presentation" class="@if ($viewname == 'sheet') active @endif">
      <a href="{{ route('character.view.sheet', $summary->characterID) }}">Sheet</a></li>
    <li role="presentation" class="@if ($viewname == 'skills') active @endif">
      <a href="{{ route('character.view.skills', $summary->characterID) }}">Skills</a></li>
    <li role="presentation" class="@if ($viewname == 'standings') active @endif">
      <a href="{{ route('character.view.standings', $summary->characterID) }}">Standings</a></li>
    <li role="presentation" class="@if ($viewname == 'journal') active @endif">
      <a href="{{ route('character.view.journal', $summary->characterID) }}">Wallet Journal</a></li>
    <li role="presentation" class="@if ($viewname == 'transactions') active @endif">
      <a href="{{ route('character.view.transactions', $summary->characterID) }}">Wallet Transactions</a></li>
  </ul>

</div>
