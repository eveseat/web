<div role="tabpanel" aria-labelledby="nav-members" id="tab-members" class="tab-pane fade">
  <table class="table table-striped table-hover no-border">
    <thead>
      <tr>
        <th>{{ trans('web::seat.main_character') }}</th>
        <th>{{ trans('web::seat.related_characters') }}</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($role->users->filter(function ($user) { return $user->name != 'admin'; }) as $member)
        <tr>
          <td>
              {!! view('web::partials.character', ['character' => $member->main_character]) !!}
          </td>
          <td>
            {!!
              $member->characters->filter(function ($item, $key) use ($member) {
                return $item->name !== $member->main_character->name;
              })->map(function ($character) {
                return view('web::partials.character-icon-hover', compact('character'))->render();
              })->implode(' ')
            !!}
          </td>
          <td>
            @if(auth()->user()->id != $member->id)
              <button type="button" class="btn btn-danger btn-xs float-right"
                      data-url="{{ route('seatcore::configuration.access.roles.edit.remove.user', ['role_id' => $role->id, 'user_id' => $member->id]) }}">
                <i class="fas fa-trash"></i>
                {{ trans('web::seat.remove') }}
              </button>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan="3">
          <input type="hidden" name="members" form="role-form" value="[]" />
          <button type="button" data-toggle="modal" data-target="#member-modal" class="btn btn-xs btn-success float-right">
            <i class="fas fa-plus"></i> {{ trans('web::seat.add') }}
          </button>
        </td>
      </tr>
    </tfoot>
  </table>
</div>