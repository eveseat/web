<div style="padding-bottom: 15px;">

  <ul class="nav nav-pills">
    <li role="presentation" class="@if ($viewname == 'assets') active @endif">
      <a href="{{ route('corporation.view.assets', $sheet->corporationID) }}">Assets</a></li>
    <li role="presentation" class="@if ($viewname == 'contacts') active @endif">
      <a href="{{ route('corporation.view.contacts', $sheet->corporationID) }}">Contacts</a></li>
    <li role="presentation" class="@if ($viewname == 'contracts') active @endif">
      <a href="{{ route('corporation.view.contracts', $sheet->corporationID) }}">Contracts</a></li>
    <li role="presentation" class="@if ($viewname == 'industry') active @endif">
      <a href="{{ route('corporation.view.industry', $sheet->corporationID) }}">Industry</a></li>
    <li role="presentation" class="@if ($viewname == 'killmails') active @endif">
      <a href="{{ route('corporation.view.killmails', $sheet->corporationID) }}">Killmails</a></li>
    <li role="presentation" class="@if ($viewname == 'market') active @endif">
      <a href="{{ route('corporation.view.market', $sheet->corporationID) }}">Market</a></li>
    <li role="presentation" class="@if ($viewname == 'summary') active @endif">
      <a href="{{ route('corporation.view.summary', $sheet->corporationID) }}">Summary</a></li>
    <li role="presentation" class="@if ($viewname == 'standings') active @endif">
      <a href="{{ route('corporation.view.standings', $sheet->corporationID) }}">Standings</a></li>
    <li role="presentation" class="@if ($viewname == 'tracking') active @endif">
      <a href="{{ route('corporation.view.tracking', $sheet->corporationID) }}">Tracking</a></li>
    <li role="presentation" class="@if ($viewname == 'journal') active @endif">
      <a href="{{ route('corporation.view.journal', $sheet->corporationID) }}">Journal</a></li>
    <li role="presentation" class="@if ($viewname == 'transactions') active @endif">
      <a href="{{ route('corporation.view.transactions', $sheet->corporationID) }}">Transactions</a></li>
  </ul>

</div>
