<div id="tab-members" class="tab-pane">
  <table class="table table-striped table-hover">
    <thead>
    <tr>
      <th>{{ trans('web::seat.main_character') }}</th>
      <th>{{ trans('web::seat.related_characters') }}</th>
      <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($role->groups->filter(function ($group) { return $group->users->first()->name != 'admin'; }) as $member)
      <tr>
        <td>
          @if(! is_null($member->main_character))
            {!! view('web::partials.character', ['character' => $member->main_character]) !!}
          @else
            {!! view('web::partials.character', ['character' => $member->users->first()]) !!}
          @endif
        </td>
        <td>
          {!!
            $member->users->filter(function ($item, $key) use ($member) {
              if (is_null($member->main_character) && ($key === 0))
                return false;

              return $item->name !== $member->main_character->name;
            })->map(function ($user) {
              return view('web::partials.character', ['character' => $user])->render();
            })->implode(' ')
          !!}
        </td>
        <td>
          @if(auth()->user()->group_id != $member->id)
            <button type="button" class="btn btn-danger btn-xs pull-right" data-groupid="{{ $member->id }}">{{ trans('web::seat.remove') }}</button>
          @endif
        </td>
      </tr>
    @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan="3">
          <input type="hidden" name="members" form="role-form" value="[]" />
          <button type="button" data-toggle="modal" data-target="#member-modal" class="btn btn-xs btn-success pull-right">{{ trans('web::seat.add') }}</button>
        </td>
      </tr>
    </tfoot>
  </table>
</div>