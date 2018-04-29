<div style="padding-bottom: 15px;">

  <ul class="nav nav-pills">
    <li role="presentation" class="@if ($sub_viewname == 'summary') active @endif">
      <a href="{{ route('corporation.view.ledger.summary', $sheet->corporation_id) }}">
        {{ trans_choice('web::seat.wallet_summary', 1) }}
      </a>
    </li>
    <li role="presentation" class="@if ($sub_viewname == 'bountyprizesbymonth') active @endif">
      <a href="{{ route('corporation.view.ledger.bountyprizesbymonth', $sheet->corporation_id) }}">
        {{ trans_choice('web::seat.bountyprizesbymonth', 2) }}
      </a>
    </li>
    <li role="presentation" class="@if ($sub_viewname == 'planetaryinteraction') active @endif">
      <a href="{{ route('corporation.view.ledger.planetaryinteraction', $sheet->corporation_id) }}">
        {{ trans_choice('web::seat.pi', 2) }}
      </a>
    </li>
    <li role="presentation" class="@if ($sub_viewname == 'missionbymonth') active @endif">
      <a href="{{ route('corporation.view.ledger.missionbymonth', $sheet->corporationID) }}">
        {{ trans_choice('web::seat.missionbymonth', 2) }}
      </a>
    </li>
    <li role="presentation" class="@if ($sub_viewname == 'incursionbymonth') active @endif">
      <a href="{{ route('corporation.view.ledger.incursionbymonth', $sheet->corporationID) }}">
        {{ trans_choice('web::seat.incursionbymonth', 2) }}
      </a>
    </li>
  </ul>

</div>
