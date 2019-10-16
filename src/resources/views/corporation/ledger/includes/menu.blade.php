<div class="pb-3">

  <ul class="nav nav-pills">
    <li role="presentation" class="nav-item">
      <a href="{{ route('corporation.view.ledger.summary', $sheet->corporation_id) }}" class="nav-link @if ($sub_viewname == 'summary') active @endif">
        {{ trans_choice('web::seat.wallet_summary', 1) }}
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('corporation.view.ledger.bountyprizesbymonth', $sheet->corporation_id) }}" class="nav-link @if ($sub_viewname == 'bountyprizesbymonth') active @endif">
        {{ trans_choice('web::seat.bountyprizesbymonth', 2) }}
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('corporation.view.ledger.planetaryinteraction', $sheet->corporation_id) }}" class="nav-link @if ($sub_viewname == 'planetaryinteraction') active @endif">
        {{ trans_choice('web::seat.pi', 2) }}
      </a>
    </li>
  </ul>

</div>
