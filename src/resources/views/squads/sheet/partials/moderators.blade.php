<h3 class="position-relative">Moderators</h3>

@foreach($squad->moderators->chunk(6) as $row)
    <div class="row mt-3">
        @foreach($row as $moderator)
            @include('web::squads.sheet.partials.moderator')
        @endforeach
    </div>
@endforeach

@can('squads.manage_moderators', $squad)
    <form method="post" action="{{ route('seatcore::squads.moderators.store', $squad) }}" class="mt-3">
        {!! csrf_field() !!}
        <div class="row justify-content-end">
            <div class="col-4">
                <div class="input-group input-group-sm">
                    <label for="squad-moderator" class="sr-only">User</label>
                    <div class="input-group mb-2">
                        <select name="user_id" placeholder="Search a moderator to add to this Squad..." class="form-control input-sm" id="squad-moderator"></select>
                        <button class="btn btn-sm btn-success d-sm-inline-block" type="submit">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form method="post" action="{{ route('seatcore::squads.destroy', $squad) }}" id="delete-squad">
        {!! csrf_field() !!}
        {!! method_field('DELETE') !!}
    </form>
@endcan