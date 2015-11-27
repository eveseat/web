<div style="padding-bottom: 15px;">

  <ul class="nav nav-pills">
    <li role="presentation" class="@if ($sub_viewname == 'roles') active @endif">
      <a href="{{ route('corporation.view.security.roles', $sheet->corporationID) }}">Roles</a></li>
    <li role="presentation" class="@if ($sub_viewname == 'titles') active @endif">
      <a href="{{ route('corporation.view.security.titles', $sheet->corporationID) }}">Titles</a></li>
    <li role="presentation" class="@if ($sub_viewname == 'log') active @endif">
      <a href="{{ route('corporation.view.security.log', $sheet->corporationID) }}">Log</a></li>
  </ul>

</div>