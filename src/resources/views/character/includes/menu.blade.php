<div style="padding-bottom: 15px;">

  <ul class="nav nav-pills">
    <li role="presentation" class="@if ($viewname == 'assets') active @endif">
      <a href="{{ route('character.view.assets', $summary->characterID) }}">
        {{ trans('web::seat.assets') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'calendar') active @endif">
      <a href="{{ route('character.view.calendar', $summary->characterID) }}">
        {{ trans('web::seat.calendar') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'channels') active @endif">
      <a href="{{ route('character.view.channels', $summary->characterID) }}">
        {{ trans('web::seat.channels') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'contacts') active @endif">
      <a href="{{ route('character.view.contacts', $summary->characterID) }}">
        {{ trans('web::seat.contacts') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'contracts') active @endif">
      <a href="{{ route('character.view.contracts', $summary->characterID) }}">
        {{ trans('web::seat.contracts') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'industry') active @endif">
      <a href="{{ route('character.view.industry', $summary->characterID) }}">
        {{ trans('web::seat.industry') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'killmails') active @endif">
      <a href="{{ route('character.view.killmails', $summary->characterID) }}">
        {{ trans('web::seat.killmails') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'mail') active @endif">
      <a href="{{ route('character.view.mail', $summary->characterID) }}">
        {{ trans('web::seat.mail') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'market') active @endif">
      <a href="{{ route('character.view.market', $summary->characterID) }}">
        {{ trans('web::seat.market') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'notifications') active @endif">
      <a href="{{ route('character.view.notifications', $summary->characterID) }}">
        {{ trans('web::seat.notifications') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'pi') active @endif">
      <a href="{{ route('character.view.pi', $summary->characterID) }}">
        {{ trans('web::seat.pi') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'research') active @endif">
      <a href="{{ route('character.view.research', $summary->characterID) }}">
        {{ trans('web::seat.research') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'sheet') active @endif">
      <a href="{{ route('character.view.sheet', $summary->characterID) }}">
        {{ trans('web::seat.sheet') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'skills') active @endif">
      <a href="{{ route('character.view.skills', $summary->characterID) }}">
        {{ trans('web::seat.skills') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'standings') active @endif">
      <a href="{{ route('character.view.standings', $summary->characterID) }}">
        {{ trans('web::seat.standings') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'journal') active @endif">
      <a href="{{ route('character.view.journal', $summary->characterID) }}">
        {{ trans('web::seat.wallet_journal') }}
      </a>
    </li>
    <li role="presentation" class="@if ($viewname == 'transactions') active @endif">
      <a href="{{ route('character.view.transactions', $summary->characterID) }}">
        {{ trans('web::seat.wallet_transactions') }}
      </a>
    </li>
  </ul>

</div>
