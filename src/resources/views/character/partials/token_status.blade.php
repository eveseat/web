@if(is_null($refresh_token))
  <span class="text-danger">
    <i class="fas fa-times-circle" data-toggle="tooltip" title="You don't own any valid token for this character."></i>
    <span class="d-none">invalid</span>
  </span>
@else
  @if($refresh_token->updated_at->lt(carbon()->subDay()))
    <span class="text-warning">
      <i class="fas fa-exclamation-circle" data-toggle="tooltip" title="Token has not been updated since more than a day, you should check your jobs."></i>
      <span class="d-none">old</span>
    </span>
  @else
    <span class="text-success">
      <i class="fas fa-check-circle" data-toggle="tooltip" title="This character has a valid registered token."></i>
      <span class="d-none">valid</span>
    </span>
  @endif
@endif
