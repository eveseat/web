<div style="padding-bottom: 15px;">

  <ul class="nav nav-pills">
    <li role="presentation" class="@if ($viewname == 'assets') active @endif">
      <a href="{{ route('corporation.view.assets', $sheet->corporationID) }}">Assets</a></li>
    <li role="presentation" class="@if ($viewname == 'contacts') active @endif">
      <a href="{{ route('corporation.view.contacts', $sheet->corporationID) }}">Contacts</a></li>
    <li role="presentation" class="@if ($viewname == 'summary') active @endif">
      <a href="{{ route('corporation.view.summary', $sheet->corporationID) }}">Summary</a></li>
  </ul>

</div>
