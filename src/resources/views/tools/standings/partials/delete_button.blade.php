<a href="{{ route('seatcore::tools.standings.edit.remove', ['entity_id' => $entity_id, 'profile_id' => $id]) }}"
   type="button" class="btn btn-danger btn-sm">
    <i class="fas fa-trash-alt"></i>
    {{ trans('web::seat.delete') }}
</a>