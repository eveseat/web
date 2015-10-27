<div style="padding-bottom: 15px;">

  <ul class="nav nav-pills">
    <li role="presentation"><a href="{{ route('character.view.skills', $summary->characterID) }}">Assets</a></li>
    <li role="presentation"><a href="{{ route('character.view.skills', $summary->characterID) }}">Calender</a></li>
    <li role="presentation"><a href="{{ route('character.view.skills', $summary->characterID) }}">Contacts</a></li>
    <li role="presentation"><a href="{{ route('character.view.skills', $summary->characterID) }}">Contracts</a></li>
    <li role="presentation"><a href="{{ route('character.view.skills', $summary->characterID) }}">Industry</a></li>
    <li role="presentation"><a href="{{ route('character.view.skills', $summary->characterID) }}">Killmails</a></li>
    <li role="presentation"><a href="{{ route('character.view.skills', $summary->characterID) }}">Mail</a></li>
    <li role="presentation"><a href="{{ route('character.view.skills', $summary->characterID) }}">Market</a></li>
    <li role="presentation"><a href="{{ route('character.view.skills', $summary->characterID) }}">Notifications</a></li>
    <li role="presentation"><a href="{{ route('character.view.skills', $summary->characterID) }}">PI</a></li>
    <li role="presentation"><a href="{{ route('character.view.skills', $summary->characterID) }}">Research</a></li>
    <li role="presentation" class="@if ($viewname == 'sheet') active @endif">
      <a href="{{ route('character.view.sheet', $summary->characterID) }}">Sheet</a></li>
    <li role="presentation" class="@if ($viewname == 'skills') active @endif">
      <a href="{{ route('character.view.skills', $summary->characterID) }}">Skills</a> </li>
    <li role="presentation"><a href="{{ route('character.view.skills', $summary->characterID) }}">Standings</a></li>
    <li role="presentation"><a href="{{ route('character.view.skills', $summary->characterID) }}">Wallet Journal</a></li>
    <li role="presentation"><a href="{{ route('character.view.skills', $summary->characterID) }}">Wallet Transactions</a></li>
  </ul>

</div>
