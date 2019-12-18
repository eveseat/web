<div class="callout callout-info d-none" data-type="rule" id="rule-template">
  <button data-dismiss="callout" class="close" type="button">&times;</button>
  <div class="form-row">
    <div class="form-group col-3">
      <label>Type</label>
      <select class="form-control rule-type">
        <option value="character" data-src="{{ route('fastlookup.characters') }}" data-path="characters" data-field="character_infos.character_id">Character</option>
        <option value="corporation" data-src="{{ route('fastlookup.corporations') }}" data-path="characters.affiliation" data-field="corporation_id">Corporation</option>
        <option value="alliance" data-src="{{ route('fastlookup.alliances') }}" data-path="characters.affiliation" data-field="alliance_id">Alliance</option>
        <option value="skill" data-src="skills.endpoint">Skill</option>
        <option value="asset" data-src="assets.endpoint">Asset</option>
      </select>
    </div>
    <div class="form-group col-2">
      <label>Operator</label>
      <select class="form-control rule-operator">
        <option value="=">Is</option>
        <option value="<>">Is Not</option>
      </select>
    </div>
    <div class="form-group col-7">
      <label>Criteria</label>
      <select class="form-control rule-criteria" style="width: 100%;"></select>
    </div>
  </div>
</div>