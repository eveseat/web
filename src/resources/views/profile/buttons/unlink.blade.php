<button type="submit" form="unlink-{{ $character->character_id }}" class="btn btn-danger btn-sm mr-1">
    <i class="fas fa-unlink"></i>
    {{ trans('web::seat.unlink') }}
</button>
<form role="form" action="{{ route('seatcore::profile.delete.character') }}" method="post" class="d-none" id="unlink-{{ $character->character_id }}">
    {!! csrf_field() !!}
    {!! method_field('DELETE') !!}
    <input type="hidden" name="character" value="{{ $character->character_id }}" />
</form>
