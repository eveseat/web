@if(is_null($refresh_token))
  <span class="text-danger align-middle">
    <i class="fas fa-times-circle" data-bs-toggle="tooltip" title="You don't own any valid token for this character."></i>
  </span>
@else
  @if($refresh_token->updated_at->lt(carbon()->subDay()))
    <span class="text-warning align-middle">
      <i class="fas fa-exclamation-circle" data-bs-toggle="tooltip" title="Token has not been updated since more than a day, you should check your jobs."></i>
    </span>
  @else
    <span class="text-success align-middle">
      <i class="fas fa-check-circle" data-bs-toggle="tooltip" title="This character has a valid registered token."></i>
    </span>
  @endif
@endif
