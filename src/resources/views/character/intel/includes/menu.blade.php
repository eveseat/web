<div class="pb-3">

  <ul class="nav nav-pills">
    <li role="presentation" class="nav-item">
      <a href="{{ route('seatcore::character.view.intel.summary', $character) }}"
         class="{{ $sub_viewname == 'summary' ? 'nav-link active' : 'nav-link' }}">
        Summary
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('seatcore::character.view.intel.standingscomparison', $character) }}"
         class="{{ $sub_viewname == 'standings' ? 'nav-link active' : 'nav-link' }}">
        Standings Compare
      </a>
    </li>
    <li role="presentation" class="nav-item">
      <a href="{{ route('seatcore::character.view.intel.notes', $character) }}"
         class="{{ $sub_viewname == 'note' ? 'nav-link active' : 'nav-link' }}">
        Notes
      </a>
    </li>
  </ul>

</div>
