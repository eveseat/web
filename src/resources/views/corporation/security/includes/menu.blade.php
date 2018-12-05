<div style="padding-bottom: 15px;">

  <ul class="nav nav-pills">
    <li role="presentation" class="@if ($sub_viewname == 'roles') active @endif">
      <a href="{{ route('corporation.view.security.roles', $sheet->corporation_id) }}">
        {{ trans_choice('web::seat.role', 2) }}
      </a>
    </li>
    <li role="presentation" class="@if ($sub_viewname == 'titles') active @endif">
      <a href="{{ route('corporation.view.security.titles', $sheet->corporation_id) }}">
        {{ trans_choice('web::seat.title', 2) }}
      </a>
    </li>
    <li role="presentation" class="@if ($sub_viewname == 'log') active @endif">
      <a href="{{ route('corporation.view.security.log', $sheet->corporation_id) }}">
        {{ trans_choice('web::seat.log', 2) }}
      </a>
    </li>
  </ul>

</div>
