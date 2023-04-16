<div class="pb-3">

  <ul class="nav nav-pills">
    <li role="presentation" class="nav-item">
      <a href="{{ route('seatcore::corporation.view.ledger.summary', $corporation->corporation_id) }}" class="nav-link @if ($sub_viewname == 'summary') active @endif">
        {{ trans_choice('web::seat.wallet_summary', 1) }}
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('seatcore::corporation.view.ledger.bounty_prizes', $corporation->corporation_id) }}" class="nav-link @if ($sub_viewname == 'bounty_prizes') active @endif">
        {{ trans_choice('web::seat.bounty_prizes', 2) }}
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('seatcore::corporation.view.ledger.planetary_interaction', $corporation->corporation_id) }}" class="nav-link @if ($sub_viewname == 'planetary_interaction') active @endif">
        {{ trans_choice('web::seat.pi', 2) }}
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('seatcore::corporation.view.ledger.offices_rentals', $corporation->corporation_id) }}" class="nav-link @if ($sub_viewname == 'offices_rentals') active @endif">
        {{ trans('web::seat.offices_rentals') }}
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('seatcore::corporation.view.ledger.industry_facility', $corporation->corporation_id) }}" class="nav-link @if ($sub_viewname == 'industry_facility') active @endif">
        {{ trans('web::seat.industry_facility') }}
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('seatcore::corporation.view.ledger.reprocessing', $corporation->corporation_id) }}" class="nav-link @if ($sub_viewname == 'reprocessing') active @endif">
        {{ trans('web::seat.reprocessing') }}
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('seatcore::corporation.view.ledger.jump_clones', $corporation->corporation_id) }}" class="nav-link @if ($sub_viewname == 'jump_clones') active @endif">
        {{ trans_choice('web::seat.jump_clones', 1) }}
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('seatcore::corporation.view.ledger.jump_bridges', $corporation->corporation_id) }}" class="nav-link @if ($sub_viewname == 'jump_bridges') active @endif">
        {{ trans('web::seat.jump_bridges') }}
      </a>
    </li>
  </ul>

</div>
