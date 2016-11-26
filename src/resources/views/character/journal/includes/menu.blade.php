<div style="padding-bottom: 15px;">

  <ul class="nav nav-pills">
    <li role="presentation" class="@if ($sub_viewname == 'journal') active @endif">
      <a href="{{ route('character.view.intel.summary', $summary->characterID) }}">
        Journal
      </a>
    </li>

  </ul>

</div>
