<div class="pb-3">

  <ul class="nav nav-pills">
    <li role="presentation" class="nav-item">
      <a href="{{ route('character.view.intel.summary', $summary->character_id) }}"
         class="{{ $sub_viewname == 'summary' ? 'nav-link active' : 'nav-link' }}">
        Summary
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('character.view.intel.standingscomparison', $summary->character_id) }}"
         class="{{ $sub_viewname == 'standings' ? 'nav-link active' : 'nav-link' }}">
        Standings Compare
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('character.view.intel.notes', $summary->character_id) }}"
         class="{{ $sub_viewname == 'note' ? 'nav-link active' : 'nav-link' }}">
        Notes
      </a>
    </li>
  </ul>

</div>
