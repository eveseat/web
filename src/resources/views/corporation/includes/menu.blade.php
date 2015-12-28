<div style="padding-bottom: 15px;">

  <ul class="nav nav-pills">
    <li role="presentation" class="@if ($viewname == 'assets') active @endif">
      <a href="{{ route('corporation.view.assets', $sheet->corporationID) }}">
        {{ trans('web::seat.assets') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'bookmarks') active @endif">
      <a href="{{ route('corporation.view.bookmarks', $sheet->corporationID) }}">
        {{ trans_choice('web::seat.bookmark', 2) }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'contacts') active @endif">
      <a href="{{ route('corporation.view.contacts', $sheet->corporationID) }}">
        {{ trans('web::seat.contacts') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'contracts') active @endif">
      <a href="{{ route('corporation.view.contracts', $sheet->corporationID) }}">
        {{ trans('web::seat.contracts') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'industry') active @endif">
      <a href="{{ route('corporation.view.industry', $sheet->corporationID) }}">
        {{ trans('web::seat.industry') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'killmails') active @endif">
      <a href="{{ route('corporation.view.killmails', $sheet->corporationID) }}">
        {{ trans('web::seat.killmails') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'market') active @endif">
      <a href="{{ route('corporation.view.market', $sheet->corporationID) }}">
        {{ trans('web::seat.market') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'pocos') active @endif">
      <a href="{{ route('corporation.view.pocos', $sheet->corporationID) }}">
        {{ trans('web::seat.pocos') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'security') active @endif">
      <a href="{{ route('corporation.view.security.roles', $sheet->corporationID) }}">
        {{ trans('web::seat.security') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'starbases') active @endif">
      <a href="{{ route('corporation.view.starbases', $sheet->corporationID) }}">
        {{ trans_choice('web::seat.starbase', 2) }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'summary') active @endif">
      <a href="{{ route('corporation.view.summary', $sheet->corporationID) }}">
        {{ trans('web::seat.summary') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'standings') active @endif">
      <a href="{{ route('corporation.view.standings', $sheet->corporationID) }}">
        {{ trans('web::seat.standings') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'tracking') active @endif">
      <a href="{{ route('corporation.view.tracking', $sheet->corporationID) }}">
        {{ trans('web::seat.tracking') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'journal') active @endif">
      <a href="{{ route('corporation.view.journal', $sheet->corporationID) }}">
        {{ trans('web::seat.wallet_journal') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'ledger') active @endif">
      <a href="{{ route('corporation.view.ledger', $sheet->corporationID) }}">
        {{ trans('web::seat.wallet_ledger') }}
      </a>
    </li>   
    <li role="presentation" class="@if ($viewname == 'transactions') active @endif">
      <a href="{{ route('corporation.view.transactions', $sheet->corporationID) }}">
        {{ trans('web::seat.wallet_transactions') }}
      </a>
    </li>
  </ul>

</div>
