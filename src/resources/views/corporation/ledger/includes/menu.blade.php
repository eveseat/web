<div style="padding-bottom: 15px;">

  <ul class="nav nav-pills">
    <li role="presentation" class="@if ($sub_viewname == 'summary') active @endif">
      <a href="{{ route('corporation.view.ledger.summary', $sheet->corporationID) }}">
        {{ trans_choice('web::seat.wallet_summary', 1) }}
      </a>
    </li>
    <li role="presentation" class="@if ($sub_viewname == 'buybymonth') active @endif">
      <a href="{{ route('corporation.view.ledger.buybymonth', $sheet->corporationID) }}">
        {{ trans_choice('web::seat.buybymonth', 2) }}
      </a>
    </li>
    <li role="presentation" class="@if ($sub_viewname == 'sellbymonth') active @endif">
      <a href="{{ route('corporation.view.ledger.sellbymonth', $sheet->corporationID) }}">
        {{ trans_choice('web::seat.sellbymonth', 2) }}
      </a>
    </li>
    <li role="presentation" class="@if ($sub_viewname == 'feebymonth') active @endif">
      <a href="{{ route('corporation.view.ledger.feebymonth', $sheet->corporationID) }}">
        {{ trans_choice('web::seat.feebymonth', 2) }}
      </a>
    </li>
    <li role="presentation" class="@if ($sub_viewname == 'bountyprizesbymonth') active @endif">
      <a href="{{ route('corporation.view.ledger.bountyprizesbymonth', $sheet->corporationID) }}">
        {{ trans_choice('web::seat.bountyprizesbymonth', 2) }}
      </a>
    </li>
    <li role="presentation" class="@if ($sub_viewname == 'planetaryinteraction') active @endif">
      <a href="{{ route('corporation.view.ledger.planetaryinteraction', $sheet->corporationID) }}">
        {{ trans_choice('web::seat.pi', 2) }}
      </a>
    </li>
  </ul>

</div>
