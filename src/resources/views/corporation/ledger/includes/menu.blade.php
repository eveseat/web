<div class="pb-3">

  <ul class="nav nav-pills">
    <li role="presentation" class="nav-item">
      <a href="{{ route('corporation.view.ledger.summary', $sheet->corporation_id) }}" class="nav-link @if ($sub_viewname == 'summary') active @endif">
        {{ trans_choice('web::seat.wallet_summary', 1) }}
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('corporation.view.ledger.bounty_prizes', $sheet->corporation_id) }}" class="nav-link @if ($sub_viewname == 'bounty_prizes') active @endif">
        {{ trans_choice('web::seat.bounty_prizes', 2) }}
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('corporation.view.ledger.planetary_interaction', $sheet->corporation_id) }}" class="nav-link @if ($sub_viewname == 'planetary_interaction') active @endif">
        {{ trans_choice('web::seat.pi', 2) }}
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('corporation.view.ledger.offices_rentals', $sheet->corporation_id) }}" class="nav-link @if ($sub_viewname == 'offices_rentals') active @endif">
        {{ trans('web::seat.offices_rentals') }}
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('corporation.view.ledger.industry_facility', $sheet->corporation_id) }}" class="nav-link @if ($sub_viewname == 'industry_facility') active @endif">
        {{ trans('web::seat.industry_facility') }}
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('corporation.view.ledger.reprocessing', $sheet->corporation_id) }}" class="nav-link @if ($sub_viewname == 'reprocessing') active @endif">
        {{ trans('web::seat.reprocessing') }}
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('corporation.view.ledger.jump_clones', $sheet->corporation_id) }}" class="nav-link @if ($sub_viewname == 'jump_clones') active @endif">
        {{ trans_choice('web::seat.jump_clones', 1) }}
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('corporation.view.ledger.jump_bridges', $sheet->corporation_id) }}" class="nav-link @if ($sub_viewname == 'jump_bridges') active @endif">
        {{ trans('web::seat.jump_bridges') }}
      </a>
    </li>
  </ul>

</div>
