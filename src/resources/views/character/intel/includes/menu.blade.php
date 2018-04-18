<div style="padding-bottom: 15px;">

  <ul class="nav nav-pills">
    <li role="presentation" class="@if ($sub_viewname == 'summary') active @endif">
      <a href="{{ route('character.view.intel.summary', $summary->character_id) }}">
        Summary
      </a>
    </li>
    <li role="presentation" class="@if ($sub_viewname == 'standings') active @endif">
      <a href="{{ route('character.view.intel.standingscomparison', $summary->character_id) }}">
        Standings Compare
      </a>
    </li>
    <li role="presentation" class="@if ($sub_viewname == 'note') active @endif">
      <a href="{{ route('character.view.intel.notes', $summary->character_id) }}">
        Notes
      </a>
    </li>
  </ul>

</div>
