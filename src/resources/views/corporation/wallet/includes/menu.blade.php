<div style="padding-bottom: 15px;">

  <ul class="nav nav-pills">
    @if(auth()->user()->has('corporation.journal'))
    <li role="presentation" class="@if($sub_viewname == 'journal') active @endif">
      <a href="{{ route('corporation.view.journal', $sheet->corporation_id) }}">Journal</a>
    </li>
    @endif
    @if(auth()->user()->has('corporation.transactions'))
    <li role="presentation" class="@if($sub_viewname == 'transactions') active @endif">
      <a href="{{ route('corporation.view.transactions', $sheet->corporation_id) }}">Transactions</a>
    </li>
    @endif
  </ul>

</div>
