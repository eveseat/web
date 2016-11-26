@if($row->toCorpOrAllianceID != 0)
  <span data-toggle="tooltip" data-placement="top" title=""
        data-original-title="Corporations / Alliances">
    <span class="label label-primary">{{ count(explode(',', $row->toCorpOrAllianceID)) }}</span>
  </span>
@endif

@if($row->toCharacterIDs)
  <span data-toggle="tooltip" data-placement="top" title=""
        data-original-title="Characters">
    <span class="label label-info">{{ count(explode(',', $row->toCharacterIDs)) }}</span>
  </span>
@endif

@if($row->toListID)
  <span data-toggle="tooltip" data-placement="top" title=""
        data-original-title="Mailing Lists">
    <span class="label label-success">{{ count(explode(',', $row->toListID)) }}</span>
  </span>
@endif
