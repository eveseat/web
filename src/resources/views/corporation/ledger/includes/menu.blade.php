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
    <li role="presentation" class="@if ($sub_viewname == 'industryfacilitytaxbymonth') active @endif">
      <a href="{{ route('corporation.view.ledger.industryfacilitytaxbymonth', $sheet->corporation_id) }}">
        {{ trans_choice('web::seat.industryfacilitytaxbymonth', 2) }}
      </a>
    </li>
    <li role="presentation" class="@if ($sub_viewname == 'jumpbridgebymonth') active @endif">
      <a href="{{ route('corporation.view.ledger.jumpbridgebymonth', $sheet->corporation_id) }}">
        {{ trans_choice('web::seat.jumpbridgebymonth', 2) }}
      </a>
    </li>
    <li role="presentation" class="@if ($sub_viewname == 'jumpclonebymonth') active @endif">
      <a href="{{ route('corporation.view.ledger.jumpclonebymonth', $sheet->corporation_id) }}">
        {{ trans_choice('web::seat.jumpclonebymonth', 2) }}
      </a>
    </li>
    <li role="presentation" class="@if ($sub_viewname == 'officerentalfeebymonth') active @endif">
      <a href="{{ route('corporation.view.ledger.officerentalfeebymonth', $sheet->corporation_id) }}">
        {{ trans_choice('web::seat.officerentalfeebymonth', 2) }}
      </a>
    </li>

    <li role="presentation" class="@if ($sub_viewname == 'reprocessingfeebymonth') active @endif">
      <a href="{{ route('corporation.view.ledger.reprocessingfeebymonth', $sheet->corporation_id) }}">
        {{ trans_choice('web::seat.reprocessingfeebymonth', 2) }}
      </a>
    </li>
  </ul>

</div>
