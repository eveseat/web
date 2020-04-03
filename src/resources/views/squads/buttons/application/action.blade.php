<div class="btn-group text-right">
  @if($row->user_id != auth()->user()->id)
    @include('web::squads.buttons.application.show')
    @include('web::squads.buttons.application.approve')
    @include('web::squads.buttons.application.reject')
  @endif
</div>