<div class="pb-3">

  <ul class="nav nav-pills">
    <li role="presentation" class="nav-item">
      <a href="{{ route('seatcore::corporation.view.security.roles', $corporation) }}" class="nav-link @if ($sub_viewname == 'roles') active @endif">
        {{ trans_choice('web::seat.role', 2) }}
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('seatcore::corporation.view.security.titles', $corporation) }}" class="nav-link @if ($sub_viewname == 'titles') active @endif">
        {{ trans_choice('web::seat.title', 2) }}
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('seatcore::corporation.view.security.log', $corporation) }}" class="nav-link @if ($sub_viewname == 'log') active @endif">
        {{ trans_choice('web::seat.log', 2) }}
      </a>
    </li>
  </ul>

</div>
